# Diagrama Base de Datos

```sql

  🗄️ ESTRUCTURA DE BASE DE DATOS - MULTI-TENANCY

  📌 BASE DE DATOS CENTRAL (Landlord DB)

  Base de datos única que gestiona todos los tenants

  CORE - Gestión de Tenants y Usuarios Globales

   -- ============================================
   -- TABLA: tenants
   -- Propósito: Almacena información de cada empresa/cliente
   -- ============================================
   CREATE TABLE tenants (
       id VARCHAR(255) PRIMARY KEY,           -- Slug único (ej: 'acme-corp')
       name VARCHAR(255) NOT NULL,            -- Nombre de la empresa
       domain VARCHAR(255) UNIQUE NOT NULL,   -- Subdominio (acme.miapp.com)
       database VARCHAR(255) NOT NULL,        -- Nombre de su BD (tenant_acme)

       -- Información de contacto
       admin_email VARCHAR(255) NOT NULL,
       phone VARCHAR(50),

       -- Configuración del tenant
       plan_id BIGINT UNSIGNED,               -- Plan contratado
       status ENUM('active', 'suspended', 'trial', 'cancelled') DEFAULT 'trial',
       trial_ends_at TIMESTAMP NULL,
       subscription_ends_at TIMESTAMP NULL,

       -- Límites y restricciones
       max_users INT DEFAULT 5,
       max_storage_mb INT DEFAULT 1000,

       -- Metadata
       settings JSON,                         -- Configuraciones personalizadas
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       deleted_at TIMESTAMP NULL
   );

   -- ============================================
   -- TABLA: domains
   -- Propósito: Gestionar múltiples dominios por tenant (opcional)
   -- ============================================
   CREATE TABLE domains (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       tenant_id VARCHAR(255) NOT NULL,
       domain VARCHAR(255) UNIQUE NOT NULL,   -- custom.domain.com
       is_primary BOOLEAN DEFAULT FALSE,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

       FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
   );

   -- ============================================
   -- TABLA: users (Central - para autenticación global)
   -- Propósito: Usuarios que pueden acceder a la plataforma
   -- ============================================
   CREATE TABLE users (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       tenant_id VARCHAR(255),                -- A qué tenant pertenece
       name VARCHAR(255) NOT NULL,
       email VARCHAR(255) UNIQUE NOT NULL,
       email_verified_at TIMESTAMP NULL,
       password VARCHAR(255) NOT NULL,

       -- Metadata de usuario global
       is_global_admin BOOLEAN DEFAULT FALSE, -- Admin de la plataforma
       last_login_at TIMESTAMP NULL,

       remember_token VARCHAR(100),
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

       FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE
   );

   -- ============================================
   -- TABLA: plans
   -- Propósito: Planes de suscripción disponibles
   -- ============================================
   CREATE TABLE plans (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(255) NOT NULL,            -- Basic, Pro, Enterprise
       slug VARCHAR(255) UNIQUE NOT NULL,
       description TEXT,

       -- Precios
       price_monthly DECIMAL(10,2),
       price_yearly DECIMAL(10,2),

       -- Límites del plan
       max_users INT,
       max_storage_mb INT,
       features JSON,                          -- Array de features incluidas

       is_active BOOLEAN DEFAULT TRUE,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
   );

   -- ============================================
   -- TABLA: subscriptions
   -- Propósito: Historial de suscripciones de tenants
   -- ============================================
   CREATE TABLE subscriptions (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       tenant_id VARCHAR(255) NOT NULL,
       plan_id BIGINT UNSIGNED NOT NULL,

       status ENUM('active', 'cancelled', 'expired', 'suspended') DEFAULT 'active',

       starts_at TIMESTAMP NOT NULL,
       ends_at TIMESTAMP NULL,
       cancelled_at TIMESTAMP NULL,

       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

       FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
       FOREIGN KEY (plan_id) REFERENCES plans(id)
   );

  ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

  📌 BASE DE DATOS POR TENANT

  Cada tenant tiene su propia base de datos con estas tablas

  CORE - Usuarios y Permisos del Tenant

   -- ============================================
   -- TABLA: users (dentro del tenant)
   -- Propósito: Usuarios específicos de esta empresa
   -- ============================================
   CREATE TABLE users (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(255) NOT NULL,
       email VARCHAR(255) UNIQUE NOT NULL,
       email_verified_at TIMESTAMP NULL,
       password VARCHAR(255) NOT NULL,

       -- Información adicional
       phone VARCHAR(50),
       avatar_url VARCHAR(255),
       position VARCHAR(100),                  -- Cargo en la empresa
       department VARCHAR(100),

       -- Estado
       is_active BOOLEAN DEFAULT TRUE,
       last_login_at TIMESTAMP NULL,

       remember_token VARCHAR(100),
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       deleted_at TIMESTAMP NULL
   );

   -- ============================================
   -- TABLA: roles
   -- Propósito: Roles dentro del tenant (Admin, Vendedor, etc.)
   -- ============================================
   CREATE TABLE roles (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(255) NOT NULL,
       slug VARCHAR(255) UNIQUE NOT NULL,
       description TEXT,
       is_system BOOLEAN DEFAULT FALSE,        -- No se puede eliminar
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
   );

   -- ============================================
   -- TABLA: permissions
   -- Propósito: Permisos granulares (crear producto, ver ventas, etc.)
   -- ============================================
   CREATE TABLE permissions (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(255) NOT NULL,
       slug VARCHAR(255) UNIQUE NOT NULL,       -- inventory.products.create
       module VARCHAR(100) NOT NULL,            -- inventory, sales, crm
       description TEXT,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
   );

   -- ============================================
   -- TABLA: role_user (Pivot)
   -- Propósito: Relación muchos a muchos entre usuarios y roles
   -- ============================================
   CREATE TABLE role_user (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       user_id BIGINT UNSIGNED NOT NULL,
       role_id BIGINT UNSIGNED NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

       FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
       FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
       UNIQUE KEY unique_user_role (user_id, role_id)
   );

   -- ============================================
   -- TABLA: permission_role (Pivot)
   -- Propósito: Relación muchos a muchos entre roles y permisos
   -- ============================================
   CREATE TABLE permission_role (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       role_id BIGINT UNSIGNED NOT NULL,
       permission_id BIGINT UNSIGNED NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

       FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
       FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
       UNIQUE KEY unique_role_permission (role_id, permission_id)
   );

   -- ============================================
   -- TABLA: audit_logs
   -- Propósito: Auditoría de acciones en el sistema
   -- ============================================
   CREATE TABLE audit_logs (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       user_id BIGINT UNSIGNED,
       action VARCHAR(255) NOT NULL,           -- created, updated, deleted
       auditable_type VARCHAR(255) NOT NULL,   -- App\Models\Product
       auditable_id BIGINT UNSIGNED NOT NULL,
       old_values JSON,
       new_values JSON,
       ip_address VARCHAR(45),
       user_agent TEXT,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

       FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
       INDEX idx_auditable (auditable_type, auditable_id),
       INDEX idx_user (user_id),
       INDEX idx_created_at (created_at)
   );

  MÓDULO INVENTARIO

   -- ============================================
   -- TABLA: categories
   -- Propósito: Categorizar productos (Electrónica, Ropa, etc.)
   -- ============================================
   CREATE TABLE categories (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       parent_id BIGINT UNSIGNED NULL,         -- Para subcategorías
       name VARCHAR(255) NOT NULL,
       slug VARCHAR(255) UNIQUE NOT NULL,
       description TEXT,
       image_url VARCHAR(255),
       is_active BOOLEAN DEFAULT TRUE,
       sort_order INT DEFAULT 0,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       deleted_at TIMESTAMP NULL,

       FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE
   );

   -- ============================================
   -- TABLA: brands
   -- Propósito: Marcas de productos
   -- ============================================
   CREATE TABLE brands (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(255) NOT NULL,
       slug VARCHAR(255) UNIQUE NOT NULL,
       logo_url VARCHAR(255),
       website VARCHAR(255),
       description TEXT,
       is_active BOOLEAN DEFAULT TRUE,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       deleted_at TIMESTAMP NULL
   );

   -- ============================================
   -- TABLA: products
   -- Propósito: Productos del inventario
   -- ============================================
   CREATE TABLE products (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       sku VARCHAR(100) UNIQUE NOT NULL,       -- Código único del producto
       barcode VARCHAR(100) UNIQUE,            -- Código de barras

       -- Información básica
       name VARCHAR(255) NOT NULL,
       slug VARCHAR(255) UNIQUE NOT NULL,
       description TEXT,
       short_description VARCHAR(500),

       -- Relaciones
       category_id BIGINT UNSIGNED,
       brand_id BIGINT UNSIGNED,

       -- Precios
       cost_price DECIMAL(15,2) DEFAULT 0,     -- Precio de costo
       selling_price DECIMAL(15,2) NOT NULL,   -- Precio de venta
       compare_price DECIMAL(15,2),            -- Precio antes de descuento

       -- Impuestos
       tax_rate DECIMAL(5,2) DEFAULT 0,        -- % de impuesto
       tax_included BOOLEAN DEFAULT FALSE,

       -- Stock
       track_inventory BOOLEAN DEFAULT TRUE,
       stock_quantity INT DEFAULT 0,
       min_stock_level INT DEFAULT 0,          -- Alerta de stock mínimo
       max_stock_level INT,

       -- Especificaciones
       weight DECIMAL(10,2),                   -- en kg
       width DECIMAL(10,2),                    -- en cm
       height DECIMAL(10,2),
       length DECIMAL(10,2),

       -- Imágenes
       main_image_url VARCHAR(255),
       images JSON,                            -- Array de URLs

       -- Metadata
       status ENUM('active', 'inactive', 'discontinued') DEFAULT 'active',
       is_featured BOOLEAN DEFAULT FALSE,
       meta_title VARCHAR(255),
       meta_description TEXT,

       created_by BIGINT UNSIGNED,
       updated_by BIGINT UNSIGNED,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       deleted_at TIMESTAMP NULL,

       FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
       FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL,
       FOREIGN KEY (created_by) REFERENCES users(id),
       FOREIGN KEY (updated_by) REFERENCES users(id),

       INDEX idx_sku (sku),
       INDEX idx_status (status),
       INDEX idx_category (category_id),
       FULLTEXT idx_search (name, description, sku)
   );

   -- ============================================
   -- TABLA: product_variants
   -- Propósito: Variantes de productos (Talla, Color, etc.)
   -- ============================================
   CREATE TABLE product_variants (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       product_id BIGINT UNSIGNED NOT NULL,
       sku VARCHAR(100) UNIQUE NOT NULL,
       barcode VARCHAR(100) UNIQUE,

       -- Atributos de la variante
       variant_name VARCHAR(255),              -- "Talla M, Color Rojo"
       attributes JSON,                        -- {"size":"M", "color":"red"}

       -- Precios específicos (sobrescriben los del producto)
       cost_price DECIMAL(15,2),
       selling_price DECIMAL(15,2),

       -- Stock específico
       stock_quantity INT DEFAULT 0,

       -- Imagen específica
       image_url VARCHAR(255),

       is_active BOOLEAN DEFAULT TRUE,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       deleted_at TIMESTAMP NULL,

       FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
       INDEX idx_product (product_id)
   );

   -- ============================================
   -- TABLA: warehouses
   -- Propósito: Almacenes/Bodegas
   -- ============================================
   CREATE TABLE warehouses (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(255) NOT NULL,
       code VARCHAR(50) UNIQUE NOT NULL,

       -- Ubicación
       address TEXT,
       city VARCHAR(100),
       state VARCHAR(100),
       country VARCHAR(100),
       postal_code VARCHAR(20),

       -- Contacto
       phone VARCHAR(50),
       email VARCHAR(255),
       manager_id BIGINT UNSIGNED,

       is_active BOOLEAN DEFAULT TRUE,
       is_default BOOLEAN DEFAULT FALSE,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       deleted_at TIMESTAMP NULL,

       FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE SET NULL
   );

   -- ============================================
   -- TABLA: stock_locations
   -- Propósito: Ubicaciones específicas dentro de almacenes
   -- ============================================
   CREATE TABLE stock_locations (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       warehouse_id BIGINT UNSIGNED NOT NULL,
       name VARCHAR(255) NOT NULL,             -- "Pasillo A-3, Estante 5"
       code VARCHAR(50) UNIQUE,
       description TEXT,
       is_active BOOLEAN DEFAULT TRUE,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

       FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE
   );

   -- ============================================
   -- TABLA: stock_movements
   -- Propósito: Movimientos de inventario (entradas, salidas, ajustes)
   -- ============================================
   CREATE TABLE stock_movements (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       product_id BIGINT UNSIGNED NOT NULL,
       variant_id BIGINT UNSIGNED NULL,
       warehouse_id BIGINT UNSIGNED NOT NULL,
       location_id BIGINT UNSIGNED NULL,

       -- Tipo de movimiento
       type ENUM('in', 'out', 'adjustment', 'transfer') NOT NULL,
       reason VARCHAR(255),                    -- purchase, sale, damaged, etc.

       -- Cantidades
       quantity INT NOT NULL,
       previous_stock INT NOT NULL,
       new_stock INT NOT NULL,

       -- Costos
       unit_cost DECIMAL(15,2),
       total_cost DECIMAL(15,2),

       -- Referencias
       reference_type VARCHAR(255),            -- Order, Purchase, Transfer
       reference_id BIGINT UNSIGNED,

       -- Metadata
       notes TEXT,
       performed_by BIGINT UNSIGNED,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

       FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
       FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
       FOREIGN KEY (warehouse_id) REFERENCES warehouses(id) ON DELETE CASCADE,
       FOREIGN KEY (location_id) REFERENCES stock_locations(id) ON DELETE SET NULL,
       FOREIGN KEY (performed_by) REFERENCES users(id) ON DELETE SET NULL,

       INDEX idx_product (product_id),
       INDEX idx_warehouse (warehouse_id),
       INDEX idx_type (type),
       INDEX idx_created_at (created_at)
   );

   -- ============================================
   -- TABLA: stock_transfers
   -- Propósito: Transferencias entre almacenes
   -- ============================================
   CREATE TABLE stock_transfers (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       transfer_number VARCHAR(50) UNIQUE NOT NULL,

       -- Almacenes origen y destino
       from_warehouse_id BIGINT UNSIGNED NOT NULL,
       to_warehouse_id BIGINT UNSIGNED NOT NULL,

       -- Estado
       status ENUM('pending', 'in_transit', 'completed', 'cancelled') DEFAULT 'pending',

       -- Metadata
       notes TEXT,
       requested_by BIGINT UNSIGNED,
       approved_by BIGINT UNSIGNED,
       shipped_at TIMESTAMP NULL,
       received_at TIMESTAMP NULL,

       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

       FOREIGN KEY (from_warehouse_id) REFERENCES warehouses(id),
       FOREIGN KEY (to_warehouse_id) REFERENCES warehouses(id),
       FOREIGN KEY (requested_by) REFERENCES users(id),
       FOREIGN KEY (approved_by) REFERENCES users(id)
   );

   -- ============================================
   -- TABLA: stock_transfer_items
   -- Propósito: Productos en una transferencia
   -- ============================================
   CREATE TABLE stock_transfer_items (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       transfer_id BIGINT UNSIGNED NOT NULL,
       product_id BIGINT UNSIGNED NOT NULL,
       variant_id BIGINT UNSIGNED NULL,

       quantity_requested INT NOT NULL,
       quantity_shipped INT DEFAULT 0,
       quantity_received INT DEFAULT 0,

       notes TEXT,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

       FOREIGN KEY (transfer_id) REFERENCES stock_transfers(id) ON DELETE CASCADE,
       FOREIGN KEY (product_id) REFERENCES products(id),
       FOREIGN KEY (variant_id) REFERENCES product_variants(id)
   );

   -- ============================================
   -- TABLA: suppliers
   -- Propósito: Proveedores de productos
   -- ============================================
   CREATE TABLE suppliers (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       code VARCHAR(50) UNIQUE NOT NULL,
       name VARCHAR(255) NOT NULL,

       -- Contacto
       email VARCHAR(255),
       phone VARCHAR(50),
       website VARCHAR(255),

       -- Dirección
       address TEXT,
       city VARCHAR(100),
       state VARCHAR(100),
       country VARCHAR(100),
       postal_code VARCHAR(20),

       -- Información comercial
       tax_id VARCHAR(100),                    -- RUC, NIT, etc.
       payment_terms VARCHAR(255),             -- "Net 30", "Prepaid", etc.

       -- Contacto principal
       contact_name VARCHAR(255),
       contact_email VARCHAR(255),
       contact_phone VARCHAR(50),

       notes TEXT,
       is_active BOOLEAN DEFAULT TRUE,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       deleted_at TIMESTAMP NULL,

       INDEX idx_code (code),
       FULLTEXT idx_search (name, email)
   );

   -- ============================================
   -- TABLA: product_supplier (Pivot)
   -- Propósito: Relación productos y sus proveedores
   -- ============================================
   CREATE TABLE product_supplier (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       product_id BIGINT UNSIGNED NOT NULL,
       supplier_id BIGINT UNSIGNED NOT NULL,

       supplier_sku VARCHAR(100),              -- SKU del proveedor
       cost_price DECIMAL(15,2),               -- Precio de compra
       min_order_quantity INT DEFAULT 1,
       lead_time_days INT,                     -- Días de entrega

       is_preferred BOOLEAN DEFAULT FALSE,     -- Proveedor preferido
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

       FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
       FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE,
       UNIQUE KEY unique_product_supplier (product_id, supplier_id)
   );

  📊 Resumen de Entidades por Módulo

  BD CENTRAL (Landlord):

   - ✅ tenants - Empresas/Clientes
   - ✅ domains - Dominios personalizados
   - ✅ users - Usuarios globales
   - ✅ plans - Planes de suscripción
   - ✅ subscriptions - Suscripciones activas

  BD POR TENANT - Core:

   - ✅ users - Usuarios del tenant
   - ✅ roles - Roles (Admin, Vendedor, etc.)
   - ✅ permissions - Permisos granulares
   - ✅ role_user - Relación usuarios-roles
   - ✅ permission_role - Relación roles-permisos
   - ✅ audit_logs - Auditoría de cambios

  BD POR TENANT - Inventario:

   - ✅ categories - Categorías de productos
   - ✅ brands - Marcas
   - ✅ products - Productos principales
   - ✅ product_variants - Variantes (talla, color, etc.)
   - ✅ warehouses - Almacenes
   - ✅ stock_locations - Ubicaciones en almacén
   - ✅ stock_movements - Movimientos de inventario
   - ✅ stock_transfers - Transferencias entre almacenes
   - ✅ stock_transfer_items - Items de transferencia
   - ✅ suppliers - Proveedores
   - ✅ product_supplier - Relación productos-proveedores
```

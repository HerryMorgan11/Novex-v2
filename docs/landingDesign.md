# Landing-desing

```markdown
erp-project/
│
├── app/
│ │
│ ├── Core/ # Clean Architecture (SIN CAMBIOS)
│ │ ├── Domain/
│ │ ├── Application/
│ │ └── Infrastructure/
│ │
│ ├── Http/
│ │ │
│ │ ├── Controllers/ # MVC tradicional (SIN CAMBIOS)
│ │ │ ├── Landing/
│ │ │ ├── Auth/
│ │ │ └── Dashboard/
│ │ │ ├── DashboardController.php
│ │ │ ├── Inventory/
│ │ │ ├── Sales/
│ │ │ ├── Accounting/
│ │ │ ├── HR/
│ │ │ └── Settings/
│ │ │
│ │ ├── Livewire/ # ⭐ NUEVO (INTERACTIVIDAD)
│ │ │ ├── Dashboard/
│ │ │ │ ├── Inventory/
│ │ │ │ │ ├── ProductTable.php
│ │ │ │ │ ├── ProductForm.php
│ │ │ │ │ ├── StockAdjuster.php
│ │ │ │ │ └── CategorySelector.php
│ │ │ │ │
│ │ │ │ ├── Sales/
│ │ │ │ │ ├── OrderTable.php
│ │ │ │ │ ├── OrderItems.php
│ │ │ │ │ └── CustomerSearch.php
│ │ │ │ │
│ │ │ │ ├── Accounting/
│ │ │ │ │ ├── InvoicePreview.php
│ │ │ │ │ └── TransactionFilter.php
│ │ │ │ │
│ │ │ │ ├── HR/
│ │ │ │ │ └── EmployeeTable.php
│ │ │ │ │
│ │ │ │ └── Settings/
│ │ │ │ ├── UserTable.php
│ │ │ │ └── SubscriptionStatus.php
│ │ │ │
│ │ │ └── Shared/
│ │ │ ├── Modal.php
│ │ │ ├── ConfirmDelete.php
│ │ │ ├── FlashMessage.php
│ │ │ └── Pagination.php
│ │ │
│ │ ├── Requests/ # Form Requests (SIN CAMBIOS)
│ │ ├── Middleware/
│ │ └── Kernel.php
│ │
│ ├── Models/
│ └── Providers/
│
├── resources/
│ │
│ ├── views/
│ │ │
│ │ ├── landing/ # LANDING (SIN CAMBIOS)
│ │ │ ├── layouts/
│ │ │ ├── pages/
│ │ │ ├── sections/
│ │ │ └── shared/
│ │ │
│ │ ├── auth/ # AUTH (SIN CAMBIOS)
│ │ │ ├── layouts/
│ │ │ └── pages/
│ │ │
│ │ └── dashboard/ # DASHBOARD
│ │ │
│ │ ├── layouts/
│ │ │ └── app.blade.php
│ │ │
│ │ ├── shared/
│ │ │ ├── sidebar.blade.php
│ │ │ ├── navbar.blade.php
│ │ │ ├── breadcrumb.blade.php
│ │ │ ├── flash.blade.php
│ │ │ ├── modal.blade.php # Usado por Livewire
│ │ │ └── button.blade.php
│ │ │
│ │ ├── pages/
│ │ │ └── home.blade.php
│ │ │
│ │ └── features/
│ │ │
│ │ ├── inventory/
│ │ │ ├── products/
│ │ │ │ ├── index.blade.php # <livewire:product-table />
│ │ │ │ ├── create.blade.php # <livewire:product-form />
│ │ │ │ ├── edit.blade.php # <livewire:product-form />
│ │ │ │ └── show.blade.php
│ │ │ │
│ │ │ ├── categories/
│ │ │ ├── stock/
│ │ │ │
│ │ │ └── partials/
│ │ │ ├── \_product-table.blade.php # Vista usada por Livewire
│ │ │ ├── \_product-form.blade.php # Vista usada por Livewire
│ │ │ └── \_stock-badge.blade.php
│ │ │
│ │ ├── sales/
│ │ ├── accounting/
│ │ ├── hr/
│ │ └── settings/
│ │
│ ├── js/
│ │ ├── landing.js
│ │ ├── dashboard.js
│ │ └── app.js
│ │
│ └── css/
│ ├── landing.css
│ ├── dashboard.css
│ └── auth.css
│
├── routes/
│ ├── web.php # Landing + Auth
│ ├── tenant.php # Dashboard (SIN CAMBIOS)
│ └── api.php
│
├── database/
├── tests/
└── public/
```

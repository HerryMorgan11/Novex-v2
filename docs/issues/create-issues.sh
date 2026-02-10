#!/bin/bash

# Script para crear issues en GitHub desde archivos markdown
# Uso: ./create-issues.sh [fase-1|fase-2|fase-3|all]

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Función para imprimir mensajes
print_info() {
    echo -e "${BLUE}ℹ${NC} $1"
}

print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

# Verificar que gh CLI esté instalado
check_gh_cli() {
    if ! command -v gh &> /dev/null; then
        print_error "GitHub CLI (gh) no está instalado"
        echo ""
        echo "Instala GitHub CLI:"
        echo "  macOS:   brew install gh"
        echo "  Ubuntu:  sudo apt install gh"
        echo "  Windows: choco install gh"
        echo ""
        echo "Luego ejecuta: gh auth login"
        exit 1
    fi
}

# Verificar autenticación
check_auth() {
    if ! gh auth status &> /dev/null; then
        print_error "No estás autenticado con GitHub CLI"
        echo ""
        echo "Ejecuta: gh auth login"
        exit 1
    fi
}

# Extraer título del archivo markdown
extract_title() {
    local file=$1
    grep "^title:" "$file" | sed 's/title: //' | tr -d '"'
}

# Extraer labels del archivo markdown
extract_labels() {
    local file=$1
    grep "^labels:" "$file" | sed 's/labels: //' | tr -d ' '
}

# Crear un issue desde un archivo
create_issue() {
    local file=$1
    local title=$(extract_title "$file")
    local labels=$(extract_labels "$file")
    
    print_info "Creando issue: $title"
    
    # Remover el frontmatter del contenido del body
    local body=$(sed '1,/^---$/d; /^---$/,+1d' "$file")
    
    # Crear el issue
    if echo "$body" | gh issue create \
        --title "$title" \
        --body-file - \
        --label "$labels" 2>/dev/null; then
        print_success "Issue creado: $title"
        return 0
    else
        print_error "Error creando issue: $title"
        return 1
    fi
}

# Crear issues de una fase
create_phase_issues() {
    local phase=$1
    local phase_dir="docs/issues/$phase"
    
    if [ ! -d "$phase_dir" ]; then
        print_error "Directorio no encontrado: $phase_dir"
        return 1
    fi
    
    echo ""
    print_info "Creando issues de $phase..."
    echo ""
    
    local count=0
    local success=0
    
    for file in "$phase_dir"/*.md; do
        if [ -f "$file" ]; then
            ((count++))
            if create_issue "$file"; then
                ((success++))
            fi
            sleep 1  # Esperar 1 segundo entre requests para no saturar API
        fi
    done
    
    echo ""
    print_success "$success de $count issues creados exitosamente en $phase"
    echo ""
}

# Mostrar uso
show_usage() {
    echo "Uso: $0 [fase-1|fase-2|fase-3|all|list|help]"
    echo ""
    echo "Comandos:"
    echo "  fase-1    Crear issues de Fase 1 (Infraestructura)"
    echo "  fase-2    Crear issues de Fase 2 (Auth + Multi-Tenancy)"
    echo "  fase-3    Crear issues de Fase 3 (Landing Page)"
    echo "  all       Crear TODOS los issues"
    echo "  list      Listar issues disponibles"
    echo "  help      Mostrar esta ayuda"
    echo ""
    echo "Ejemplos:"
    echo "  $0 fase-1          # Crear solo issues de fase 1"
    echo "  $0 all             # Crear todos los issues"
    echo ""
}

# Listar issues disponibles
list_issues() {
    echo ""
    print_info "Issues disponibles:"
    echo ""
    
    for phase_dir in docs/issues/fase-*; do
        if [ -d "$phase_dir" ]; then
            phase=$(basename "$phase_dir")
            echo -e "${BLUE}$phase:${NC}"
            
            for file in "$phase_dir"/*.md; do
                if [ -f "$file" ]; then
                    title=$(extract_title "$file")
                    echo "  - $title"
                fi
            done
            echo ""
        fi
    done
}

# Crear todos los issues
create_all_issues() {
    print_warning "¿Estás seguro que quieres crear TODOS los issues? (y/n)"
    read -r response
    
    if [[ ! "$response" =~ ^[Yy]$ ]]; then
        print_info "Operación cancelada"
        exit 0
    fi
    
    for phase in fase-1 fase-2 fase-3; do
        if [ -d "docs/issues/$phase" ]; then
            create_phase_issues "$phase"
        fi
    done
}

# Script principal
main() {
    # Cambiar al directorio raíz del proyecto
    cd "$(dirname "$0")/../.." || exit 1
    
    # Verificar prerequisitos
    check_gh_cli
    check_auth
    
    # Verificar argumentos
    if [ $# -eq 0 ]; then
        show_usage
        exit 1
    fi
    
    case "$1" in
        fase-1|fase-2|fase-3)
            create_phase_issues "$1"
            ;;
        all)
            create_all_issues
            ;;
        list)
            list_issues
            ;;
        help|--help|-h)
            show_usage
            ;;
        *)
            print_error "Comando desconocido: $1"
            echo ""
            show_usage
            exit 1
            ;;
    esac
    
    echo ""
    print_success "¡Listo! Revisa los issues en GitHub:"
    echo "https://github.com/HerryMorgan11/Novex-v2/issues"
    echo ""
}

# Ejecutar script
main "$@"

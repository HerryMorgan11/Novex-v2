#!/usr/bin/env python3
"""
Script para crear issues de GitHub desde archivos markdown
Uso: python3 create_issues.py [fase-1|fase-2|fase-3|all]
"""

import os
import sys
import re
from pathlib import Path

def extract_frontmatter(content):
    """Extrae el frontmatter YAML y el body del markdown"""
    lines = content.split('\n')
    if lines[0].strip() == '---':
        # Encontrar el segundo ---
        end_idx = None
        for i in range(1, len(lines)):
            if lines[i].strip() == '---':
                end_idx = i
                break
        
        if end_idx:
            frontmatter_lines = lines[1:end_idx]
            body_lines = lines[end_idx+1:]
            
            # Parsear frontmatter
            frontmatter = {}
            for line in frontmatter_lines:
                if ':' in line:
                    key, value = line.split(':', 1)
                    frontmatter[key.strip()] = value.strip()
            
            body = '\n'.join(body_lines).strip()
            return frontmatter, body
    
    return {}, content

def create_issue_from_file(filepath):
    """Lee un archivo markdown y retorna los datos para crear la issue"""
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    frontmatter, body = extract_frontmatter(content)
    
    title = frontmatter.get('title', '').strip('"')
    labels = frontmatter.get('labels', '').split(',')
    labels = [l.strip() for l in labels if l.strip()]
    
    return {
        'title': title,
        'body': body,
        'labels': labels,
        'filepath': filepath
    }

def print_issue_create_command(issue_data):
    """Imprime el comando gh para crear la issue"""
    title = issue_data['title']
    labels = ','.join(issue_data['labels'])
    filepath = issue_data['filepath']
    
    print(f"\n# Crear issue: {title}")
    print(f"gh issue create \\")
    print(f'  --title "{title}" \\')
    print(f"  --label \"{labels}\" \\")
    print(f"  --body-file <(sed '1,/^---$/d; /^---$/d' \"{filepath}\")")
    print()

def process_phase(phase):
    """Procesa todas las issues de una fase"""
    phase_dir = Path(f"docs/issues/{phase}")
    
    if not phase_dir.exists():
        print(f"❌ Error: Directorio {phase_dir} no encontrado")
        return
    
    print(f"\n{'='*60}")
    print(f"📋 Procesando {phase.upper()}")
    print(f"{'='*60}")
    
    md_files = sorted(phase_dir.glob('*.md'))
    
    if not md_files:
        print(f"⚠️  No se encontraron archivos .md en {phase_dir}")
        return
    
    for md_file in md_files:
        try:
            issue_data = create_issue_from_file(md_file)
            print_issue_create_command(issue_data)
        except Exception as e:
            print(f"❌ Error procesando {md_file}: {e}")

def main():
    if len(sys.argv) < 2:
        print("Uso: python3 create_issues.py [fase-1|fase-2|fase-3|all]")
        print("\nEjemplos:")
        print("  python3 create_issues.py fase-1")
        print("  python3 create_issues.py all")
        sys.exit(1)
    
    command = sys.argv[1].lower()
    
    # Cambiar al directorio del proyecto
    script_dir = Path(__file__).parent
    os.chdir(script_dir)
    
    print("🚀 Generador de comandos para crear issues en GitHub")
    print("=" * 60)
    
    if command == 'all':
        for phase in ['fase-1', 'fase-2', 'fase-3']:
            process_phase(phase)
    elif command in ['fase-1', 'fase-2', 'fase-3']:
        process_phase(command)
    else:
        print(f"❌ Comando desconocido: {command}")
        print("Usa: fase-1, fase-2, fase-3 o all")
        sys.exit(1)
    
    print("\n" + "="*60)
    print("✅ Comandos generados!")
    print("\nPara ejecutar los comandos, copia y pega cada bloque en tu terminal")
    print("Asegúrate de tener gh CLI instalado y autenticado:")
    print("  gh auth login")
    print("="*60)

if __name__ == '__main__':
    main()

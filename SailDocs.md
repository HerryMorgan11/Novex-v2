Alias para sail y en vez de llamar a vendor/bin/sail hacemos sail up:

    alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'

curl -X POST http://127.0.0.1:80/api/inventario/transportes \
 -H "Content-Type: application/json" \
 -H "Authorization: Bearer b7d8396e6c5f048501237cabab021671c87ab08b6a137e7d7e7776954b96fe79" \
 -H "X-Tenant-Id: 019db103-befd-73f6-a9f1-62601860b34b" \
 -d '{
"referencia": "TR-EXT-001",
"proveedor": "Empresa Logística S.A.",
"origen": "Madrid",
"destino": "Barcelona",
"placa": "1234ABC",
"transportista": "Juan García",
"fecha_prevista": "2026-04-25T10:00:00",
"observaciones": "Frágil",
"lineas": [
{
"referencia_producto": "SKU-001",
"nombre": "Tornillo M8 x 25",
"cantidad": 1000,
"unidad": "piezas"
}
]
}'

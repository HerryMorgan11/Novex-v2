# Estructura del Proyecto Novex v2

Fecha de generación: 23 de abril de 2026

```
novex-v2/
├── Dockerfile
├── ESTRUCTURA_PROYECTO.md
├── README.md
├── SailDocs.md
├── Tenant::all
├── artisan
├── compose.yaml
├── composer.json
├── composer.lock
├── eslint.config.js
├── package-lock.json
├── package.json
├── phpstan.neon.dist
├── phpunit.xml
├── pint.json
├── t_019d30c2-f696-73fe-8ff9-b9da29b9b57b
├── t_019d30c2-f696-73fe-8ff9-b9da29b9b57b.sqlite
├── vite.config.js
├── app/
│   ├── Actions/
│   │   ├── Fortify/   -> Sistema de validación
│   │   │   ├── CreateNewUser.php
│   │   │   ├── PasswordValidationRules.php
│   │   │   ├── ResetUserPassword.php
│   │   │   ├── UpdateUserPassword.php
│   │   │   └── UpdateUserProfileInformation.php
│   │   ├── Inventario/ -> Acciones logica de negocio de Inventario
│   │   │   ├── ConfirmarEntregaExpedicion.php
│   │   │   ├── MoverAProduccion.php
│   │   │   ├── PrepararExpedicion.php
│   │   │   ├── RecibirLote.php
│   │   │   └── RegistrarTransporteDesdeApi.php
│   │   └── Tenancy/ -> Creación del tenant
│   │       └── CreateTenantAction.php
│   ├── Console/ -> Ejecutamos estos comandos en consola
│   │   └── Commands/
│   │       └── ProvisionTenant.php
│   ├── Enums/
│   │   └── Inventario/
│   │       ├── ExpedicionEstado.php
│   │       ├── LoteEstado.php
│   │       ├── MovimientoTipo.php
│   │       ├── ProductoValidacion.php
│   │       └── TransporteEstado.php
│   ├── Http/
│   │   ├── Controllers/ -> Controladores
│   │   │   ├── AuthController.php
│   │   │   ├── CompanyController.php
│   │   │   ├── ControlPanelController.php
│   │   │   ├── Controller.php
│   │   │   ├── DashboardController.php
│   │   │   ├── ProvisioningController.php
│   │   │   ├── ReminderController.php
│   │   │   ├── ReminderListController.php
│   │   │   ├── SettingsController.php
│   │   │   ├── SubtaskController.php
│   │   │   ├── Api/
│   │   │   │   └── Inventario/
│   │   │   ├── Auth/
│   │   │   │   └── GoogleController.php
│   │   │   └── Dashboard/
│   │   │       └── Features/
│   │   ├── Middleware/ -> Middelware accion intermedia
│   │   │   ├── AutenticarApiInventario.php
│   │   │   ├── CheckHasTenant.php
│   │   │   ├── InitializeTenancyFromApi.php
│   │   │   ├── InitializeTenancyFromUser.php
│   │   │   └── InitializeTenant.php
│   │   └── Requests/
│   │       ├── Company/
│   │       │   └── StoreCompanyRequest.php
│   │       ├── Inventario/
│   │       │   └── Almacen/
│   │       ├── Notes/
│   │       │   ├── NoteRequest.php
│   │       │   ├── StoreNoteRequest.php
│   │       │   └── UpdateNoteRequest.php
│   │       ├── ReminderLists/
│   │       │   ├── ReminderListRequest.php
│   │       │   ├── StoreReminderListRequest.php
│   │       │   └── UpdateReminderListRequest.php
│   │       ├── Reminders/
│   │       │   ├── ReminderRequest.php
│   │       │   ├── StoreReminderRequest.php
│   │       │   └── UpdateReminderRequest.php
│   │       └── Subtasks/
│   │           ├── StoreSubtaskRequest.php
│   │           ├── SubtaskRequest.php
│   │           └── UpdateSubtaskRequest.php
│   ├── Models/
│   │   ├── Domain.php
│   │   ├── Note.php
│   │   ├── Reminder.php
│   │   ├── ReminderList.php
│   │   ├── SocialAccount.php
│   │   ├── Subtask.php
│   │   ├── Tenant.php
│   │   ├── TenantAuditLog.php
│   │   ├── TenantInvitation.php
│   │   ├── TenantMembership.php
│   │   ├── TenantProvisioning.php
│   │   ├── TenantSetting.php
│   │   ├── User.php
│   │   └── Inventario/
│   │       ├── Almacen.php
│   │       ├── ApiTokenInventario.php
│   │       ├── CategoriaProducto.php
│   │       ├── DetalleMovimiento.php
│   │       ├── Estanteria.php
│   │       ├── Expedicion.php
│   │       ├── LineaExpedicion.php
│   │       ├── LineaTransporte.php
│   │       ├── Lote.php
│   │       ├── Movimiento.php
│   │       ├── Producto.php
│   │       ├── Proveedor.php
│   │       ├── Stock.php
│   │       ├── Transporte.php
│   │       ├── TrazabilidadEvento.php
│   │       ├── Ubicacion.php
│   │       ├── UnidadMedida.php
│   │       └── Zona.php
│   ├── Notifications/
│   │   └── CustomResetPassword.php
│   ├── Policies/
│   │   ├── ReminderListPolicy.php
│   │   ├── ReminderPolicy.php
│   │   └── SubtaskPolicy.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   ├── FortifyServiceProvider.php
│   │   ├── ModuloInventarioServiceProvider.php
│   │   └── TenancyServiceProvider.php
│   ├── Services/
│   │   └── DashboardService.php
│   ├── Tenancy/
│   │   └── Jobs/
│   │       └── FinalizeProvisioning.php
│   └── mail/
│       └── TestEmail.php
├── bootstrap/
│   ├── app.php
│   ├── providers.php
│   └── cache/
│       ├── packages.php
│       └── services.php
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── fortify.php
│   ├── livewire.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   ├── session.php
│   └── tenancy.php
├── database/
│   ├── database.sqlite
│   ├── t_019d30c2-f696-73fe-8ff9-b9da29b9b57b
│   ├── factories/
│   │   └── UserFactory.php
│   ├── migrations/
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 2026_02_19_150311_create_users_table.php
│   │   ├── 2026_02_19_150312_000_create_tenants_table.php
│   │   ├── 2026_02_19_150312_create_permission_tables.php
│   │   ├── 2026_02_19_150312_create_social_accounts_table.php
│   │   ├── 2026_02_19_150312_create_tenant_memberships_table.php
│   │   ├── 2026_02_19_150312_create_tenant_provisionings_table.php
│   │   ├── 2026_02_19_150313_create_domains_table.php
│   │   ├── 2026_02_19_150314_add_current_tenant_fk_to_users_table.php
│   │   ├── 2026_02_19_150700_add_fields_to_tenants_table.php
│   │   ├── 2026_02_19_160000_create_password_reset_tokens_table.php
│   │   ├── 2026_02_19_160001_create_tenant_invitations_table.php
│   │   ├── 2026_02_19_160002_create_tenant_audit_logs_table.php
│   │   ├── 2026_02_19_160003_create_tenant_settings_table.php
│   │   ├── 2026_02_19_160004_add_performance_indexes.php
│   │   ├── 2026_02_19_160945_add_two_factor_columns_to_users_table.php
│   │   ├── 2026_02_20_153453_create_jobs_table.php
│   │   ├── 2026_03_16_000001_add_company_fields_to_tenants_table.php
│   │   └── tenant/
│   │       ├── 2025_12_27_185014_create_productos_table.php
│   │       ├── 2025_12_27_185015_create_proveedor_table.php
│   │       ├── 2026_01_14_000002_create_almacenes_base_table.php
│   │       ├── 2026_01_14_000003_create_stock_base_table.php
│   │       ├── 2026_01_14_000004_create_movimientos_base_table.php
│   │       ├── 2026_01_23_161807_create_recepciones_table.php
│   │       ├── 2026_03_27_205546_create_notes_table.php
│   │       ├── 2026_04_06_000001_create_reminder_lists_table.php
│   │       ├── 2026_04_06_000002_create_reminders_table.php
│   │       ├── 2026_04_06_000003_create_subtasks_table.php
│   │       ├── 2026_04_06_000004_create_tags_table.php
│   │       ├── 2026_04_06_000005_create_reminder_tag_table.php
│   │       ├── 2026_04_13_000001_add_indexes_to_notes_and_reminders.php
│   │       ├── 2026_04_16_000001_make_product_columns_nullable.php
│   │       ├── 2026_04_18_000001_extend_inventario_tables.php
│   │       ├── 2026_04_18_000002_create_expediciones_table.php
│   │       ├── 2026_04_18_000003_create_trazabilidad_api_tables.php
│   │       └── 2026_04_21_000001_fix_stock_unique_per_lote.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── InventarioSeeder.php
│       └── ReminderSeeder.php
├── docker/
│   └── nginx/
│       └── conf.d/
│           └── default.conf
├── docs/
│   ├── MIGRACIONES_BD_CENTRAL.md
│   ├── VERIFICACION_MULTITENANCY.md
│   ├── landingDesign.md
│   └── config-multy-tenancy/
│       ├── configuracion.md
│       └── tenant-config.md
├── novex-docs/
│   ├── README.md
│   ├── docusaurus.config.js
│   ├── package-lock.json
│   ├── package.json
│   ├── sidebars.js
│   ├── docs/
│   │   ├── architecture/
│   │   │   ├── database.md
│   │   │   ├── overview.md
│   │   │   └── specifications.md
│   │   ├── features/
│   │   │   ├── landing-design.md
│   │   │   └── landing.md
│   │   ├── getting-started/
│   │   │   └── quick-start.md
│   │   ├── project/
│   │   │   ├── phases.md
│   │   │   ├── roadmap.md
│   │   │   └── issues/
│   │   │       ├── INICIO_RAPIDO.md
│   │   │       ├── README.md
│   │   │       ├── create-issues.sh
│   │   │       ├── creating-issues.md
│   │   │       ├── read-me-issues.md
│   │   │       ├── templates.md
│   │   │       ├── visual-guide.md
│   │   │       ├── fase-1/
│   │   │       ├── fase-2/
│   │   │       ├── fase-3/
│   │   │       ├── fase-4/
│   │   │       └── fase-5/
│   │   └── tutorial-basics/
│   │       ├── congratulations.md
│   │       ├── create-a-blog-post.md
│   │       ├── create-a-document.md
│   │       ├── create-a-page.md
│   │       └── deploy-your-site.md
│   ├── src/
│   │   ├── components/
│   │   │   └── HomepageFeatures/
│   │   │       ├── index.js
│   │   │       └── styles.module.css
│   │   ├── css/
│   │   │   └── custom.css
│   │   └── pages/
│   │       ├── index.js
│   │       ├── index.module.css
│   │       └── markdown-page.md
│   └── static/
│       ├── img/
│       │   ├── docusaurus-social-card.jpg
│       │   ├── docusaurus.png
│       │   ├── favicon.ico
│       │   ├── logo-novex.png
│       │   ├── logo.svg
│       │   ├── undraw_docusaurus_mountain.svg
│       │   ├── undraw_docusaurus_react.svg
│       │   └── undraw_docusaurus_tree.svg
│       └── memoria/
│           └── Q__8HGJKSWKvlbOKLdTtkg_ad50d57e3f79455783a12f606c297af1_Google-Cybersecurity-Certificate-glossary.docx
├── public/
│   ├── apple-touch-icon.png
│   ├── favicon.ico
│   ├── favicon.svg
│   ├── index.php
│   ├── robots.txt
│   └── assets/
│       ├── background/
│       │   ├── fondo-forms.jpg
│       │   └── fondo-login.jpg
│       ├── logo/
│       │   └── logo-novex-color.png
│       └── pdf/
│           └── TermsAndCoinditions.pdf
├── resources/
│   ├── css/
│   │   ├── app.css
│   │   ├── auth/
│   │   │   ├── auth.css
│   │   │   └── register.css
│   │   ├── dashboard/
│   │   │   ├── control-panel.css
│   │   │   ├── create-company-modal.css
│   │   │   ├── general-dashboard.css
│   │   │   ├── navbar.css
│   │   │   ├── settings-profile.css
│   │   │   ├── sidebar.css
│   │   │   └── features/
│   │   │       ├── calendario.css
│   │   │       ├── dashboard.css
│   │   │       ├── inventario.css
│   │   │       ├── notes.css
│   │   │       ├── inventario/
│   │   │       ├── reminders/
│   │   │       └── settings/
│   │   └── landing/
│   │       ├── general-style.css
│   │       ├── sections/
│   │       │   ├── about.css
│   │       │   ├── contabilidad.css
│   │       │   ├── precios.css
│   │       │   ├── rh.css
│   │       │   ├── contabilidad/
│   │       │   ├── crm/
│   │       │   ├── home/
│   │       │   ├── inventario/
│   │       │   └── recursos-humanos/
│   │       └── shared/
│   │           ├── footer.css
│   │           └── navbar.css
│   ├── images/
│   │   ├── background/
│   │   │   ├── fondo-forms.jpg
│   │   │   └── fondo-login.jpg
│   │   └── logo/
│   │       └── logo-novex-color.png
│   ├── js/
│   │   ├── app.js
│   │   ├── controlPanel/
│   │   │   └── navigation.js
│   │   ├── dashboard/
│   │   │   ├── createCompanyModal.js
│   │   │   ├── sidebar.js
│   │   │   ├── subtasks.js
│   │   │   └── features/
│   │   │       ├── calendario.js
│   │   │       └── dashboard.js
│   │   └── settings/
│   │       └── settings.js
│   └── views/
│       ├── auth/
│       │   ├── forgot-password.blade.php
│       │   ├── login.blade.php
│       │   ├── provisioning.blade.php
│       │   ├── register.blade.php
│       │   ├── reset-password-mail.blade.php
│       │   ├── reset-password.blade.php
│       │   └── verify-email.blade.php
│       ├── components/
│       │   ├── control-panel/
│       │   │   └── navegation.blade.php
│       │   └── settings/
│       │       └── profile.blade.php
│       ├── dashboard/
│       │   ├── dashboard.blade.php
│       │   ├── app/
│       │   │   ├── dashboard.blade.php
│       │   │   └── home.blade.php
│       │   ├── features/
│       │   │   ├── calendario/
│       │   │   ├── control-panel/
│       │   │   ├── inventario/
│       │   │   ├── notes/
│       │   │   ├── reminders/
│       │   │   └── settings/
│       │   ├── partials/
│       │   │   └── create-company-modal.blade.php
│       │   └── shared/
│       │       ├── navbar.blade.php
│       │       └── sidebar.blade.php
│       └── landing/
│           ├── layout/
│           │   └── app.blade.php
│           ├── pages/
│           │   ├── about.blade.php
│           │   ├── contabilidad.blade.php
│           │   ├── crm.blade.php
│           │   ├── home.blade.php
│           │   ├── inventario.blade.php
│           │   ├── pricing.blade.php
│           │   └── recursos-humanos.blade.php
│           ├── sections/
│           │   ├── about/
│           │   ├── contabilidad/
│           │   ├── crm/
│           │   ├── home/
│           │   ├── inventario/
│           │   ├── precios/
│           │   └── recursos-humanos/
│           └── shared/
│               ├── footer.blade.php
│               └── navbar.blade.php
├── routes/
│   ├── api.php
│   ├── central.php
│   ├── console.php
│   ├── tenant.php
│   └── web.php
├── scripts/
│   └── dev-setup.sh
├── storage/
│   ├── app/
│   │   ├── private/
│   │   └── public/
│   ├── framework/
│   │   ├── cache/
│   │   │   └── data/
│   │   ├── sessions/
│   │   │   └── XFLUYoh5DKA369rYIqVxIYwKvAjZa1e4O289mO9o
│   │   ├── testing/
│   │   └── views/
│   │       ├── 0138746e4617bc6020ffb1acf9087592.php
│   │       ├── 01de25a66505c2398d99e5ad05534e3c.php
│   │       ├── 0309813f171fb2adcea2d7bbb707b5f9.php
│   │       ├── 03d70c36ff3e116d1430af4f4e0c143e.php
│   │       ├── 04a2d9e5f8a68c83b2126ba75e609832.php
│   │       ├── 04ffd6b2dfed7e9b26b99b01de8c66cb.php
│   │       ├── 050b5caeb0b94da6fcf7f6fb532d0282.php
│   │       ├── 05bca1e83953b36eeee5665fdd6b7448.php
│   │       ├── 071c8114ec82a36ba1bca2d38c328859.php
│   │       ├── 09ee47cb6b2ead6fe59c09eda6d900b2.php
│   │       ├── 0bb859c0b0cc2156350be25c3619a135.php
│   │       ├── 0c3777120a4d74dbc6e3d2528be940dc.php
│   │       ├── 0cb993ed0ad8ecdc74d29d668d211761.php
│   │       ├── 0cd3f42f50837d1c0987bdb8d9888ff2.php
│   │       ├── 0d91cc84ca3ef31e353ece892735402b.php
│   │       ├── 0f4152eefea60d852f111591d5590883.php
│   │       ├── 10cf8f748072fecd3c8401eaffb77e60.php
│   │       ├── 11a0ef2085d2be01db7ab52e8164581c.php
│   │       ├── 132ed7c1a0089193a75e723c381c6251.php
│   │       ├── 136a7c656d1a0ed4e2d0901fc10aa84a.php
│   │       ├── 1714a096d2cecb9448c180c3c32ad105.php
│   │       ├── 190b2d6194cbfe4fd5a6fbe8d8f9775a.php
│   │       ├── 1bbc6102c081b21bf9f2f593a9b0e218.php
│   │       ├── 1c0378a9025880831ead2248d25e2a76.php
│   │       ├── 1c9b80104cf0d1426fefb059736249dc.php
│   │       ├── 1d2391682eab560de975ab578c3f6033.php
│   │       ├── 1fe969fc75bed4086ac298aea90d297c.php
│   │       ├── 205f5fe2d5a638a824094790e6076cad.php
│   │       ├── 210914a66395fe2d17c8d38f0c8182e3.php
│   │       ├── 221d3db598404432839d1e388738b30a.php
│   │       ├── 225ce8d7efa75d5552ff8e9e7a96cf02.php
│   │       ├── 2300a431f3a2fdbcb05a6fa60ef5bb93.php
│   │       ├── 240c036451647586a72ab29e5f9f0bef.php
│   │       ├── 25559de93bb3f75197f65d31431cc854.php
│   │       ├── 25b0d221b0b3a2f9f0f84bf62ea8d4d3.php
│   │       ├── 26328897c1aa10f79e9e2d0d5329b82e.php
│   │       ├── 267e4128d55cf0cbc9279ef8785726e7.php
│   │       ├── 269b0fee0189e73c1136e66adf27750b.php
│   │       ├── 275c7c02e2528e6029079c885e2d2418.php
│   │       ├── 2c1a80297c68f56dd6a046ea8d655c52.php
│   │       ├── 2cd01824a837fcbad18f4451278dfa3d.php
│   │       ├── 2e6b5d12424a1f1d073df1cf3e887026.php
│   │       ├── 2f38a3fc8a895ab48cd70a819d95a5ca.php
│   │       ├── 2f4575054e2433d6ad3b7ae62a109669.php
│   │       ├── 2f9d6af13de341db88a61aab59ab2628.php
│   │       ├── 35656cb5de510fa26c7218ca3dc2b624.php
│   │       ├── 3634c555710e17205d94c731420c5de4.php
│   │       ├── 377bad0e5a5e47b80ba039ed8482c887.php
│   │       ├── 3acf202fb1c236307cb6559e191adedf.php
│   │       ├── 3e32abeba9b2845121b75fb6f4c62247.php
│   │       ├── 3e9fb5621b2e3e45292d0ca6db4e756e.php
│   │       ├── 4175411cc0d6c423540119277f1bf7c6.php
│   │       ├── 419551074a50fbe34ef86a0e07ca7190.php
│   │       ├── 42cbb7f239f1213debda03a2a5cecf9a.php
│   │       ├── 4365350840dea8b5afac758f043feafd.php
│   │       ├── 440fbacaaf05f69e9e11f80c2816f937.php
│   │       ├── 442e01494c474de743845faab60cb8a9.php
│   │       ├── 4453ffd30cf67563bba73360e1a98d6d.php
│   │       ├── 457919a6dd8ba11ea3cfbe7a32911e0b.php
│   │       ├── 4591d29b3976ea7dfd12a787e080df17.php
│   │       ├── 45d7096f7b58fcc993f6b70b774f8791.php
│   │       ├── 45fd77ccb6930312dae5b684702095d1.php
│   │       ├── 4688a1a55a1f3e891be4bbb0d934df98.php
│   │       ├── 47c27f6cdbf2ee289e2a7d7c895a4c09.php
│   │       ├── 48a561c56b98dae043457dfe54c7f5dd.php
│   │       ├── 4a4f4a15d80c6aeaa13dc99d23f9d697.php
│   │       ├── 4a58daf1fa1bf1e549a5632216b8eca7.php
│   │       ├── 4d1ad5146ce97e56a90e583366907bab.php
│   │       ├── 4d26b82811634e9b4cd897aec6c8dfd9.php
│   │       ├── 4dacd80b5e1c55053b4f638c21c91bae.php
│   │       ├── 4eca32fe178540bcab8cd22306836592.php
│   │       ├── 4ee3ab6db1fa8b9c29091a68e99cacf9.php
│   │       ├── 4fb32473260981004f6f44d525af9778.php
│   │       ├── 4fc1040fd674004bb51f007b9eb993dc.php
│   │       ├── 508f1950e4e8efe8ee069df2bd0b7937.php
│   │       ├── 521a94cc027c5265f8e1bdafd71dd49d.php
│   │       ├── 53540d749f76d38dfc4d2c006eff23c4.php
│   │       ├── 54536905f85809794b64e5f429f26195.php
│   │       ├── 578865ac6b8ec0284a92eaeb209a8316.php
│   │       ├── 59a53ddb5014b771cb46e5d7b4143bc2.php
│   │       ├── 5a2ea29a7d56420cd213c26ae69cbd6e.php
│   │       ├── 5a8db89ad834c8f8ca915c47e45fe8fd.php
│   │       ├── 5ca7947c4abd4444befcfd9659a16380.php
│   │       ├── 5d354b2893d50f9829507cb5db08ec2b.php
│   │       ├── 5e8631656c3889d026f87f38ef22a58e.php
│   │       ├── 5eab5800bdf02a9dc5005a11c1b7e8d2.php
│   │       ├── 5eb4f4f0212eecb957dbd68631f9e1aa.php
│   │       ├── 5f65842e76da014c6209b98c922393ee.php
│   │       ├── 64cf5b8ed0e336e88b8f9706d63495bb.php
│   │       ├── 65127e82ffb9b426e54bd90f1bdef4df.php
│   │       ├── 659889c5f3ec4a31a1ff1f64fe66864c.php
│   │       ├── 65a501a2e2bae32d0e48b98429ab4fac.php
│   │       ├── 65d21cb5a9c46121440d93572f0e2514.php
│   │       ├── 664a2679f9dde650c530a578de090cc9.php
│   │       ├── 66f93ee54dd8eb28e8bab415a5deb3c0.php
│   │       ├── 6871547a206536d3d96e55663564f99c.php
│   │       ├── 6b0b7831ae9d915920208dd3492b2b4d.php
│   │       ├── 6cd8965759392e3c15ce560af6e2a501.php
│   │       ├── 6e76e45e811433e6fc9e66fb3b30e60f.php
│   │       ├── 6f7d30915538961ccb48e148b50dff1e.php
│   │       ├── 6f8dc7bde97ea89b8aa5e7e3c794d591.php
│   │       ├── 71a6951b9841487b15053ac2e0715a8c.php
│   │       ├── 720489ef37d42be9bfe603cf4ab26b17.php
│   │       ├── 7367d9bff522de8b1aa88dee731faa76.php
│   │       ├── 738e451882836118da15d9453e742127.php
│   │       ├── 73a7cf4c86d675726ffa2f8f7a8e004c.php
│   │       ├── 74c10e6520bb5319af2b12de644c8b02.php
│   │       ├── 77db285a2c045a53df8fed0e1f83fb8b.php
│   │       ├── 78190cec6318e2b9ea24be0e4d379828.php
│   │       ├── 79afe6b3bc018ea3627950a8abbd8c50.php
│   │       ├── 7ab7d5c4b4b95b090d192a15c186d211.php
│   │       ├── 7c4c5d229eacf92bdc61157cf0e2fcb2.php
│   │       ├── 7e844fae339e32a0c3035ceb1b159eb4.php
│   │       ├── 801f7ffb9836049001f9485aa9814f2e.php
│   │       ├── 80206a5900f7d9f27081a913e0b15676.php
│   │       ├── 8179d71f3659ce97b3510ab9b416578c.php
│   │       ├── 8200d78d1269662c2e1ba8b7bf12d0c9.php
│   │       ├── 82a0fa7e71ecb4853da89d341a3baf7e.php
│   │       ├── 830b0617d90c70bb1bd52d66317a2cf5.php
│   │       ├── 83cc3fc9243bd5a330ff51e4d424bae6.php
│   │       ├── 854f8d91216e61b32fd7b77434e6da81.php
│   │       ├── 863c14de5ad17d53de16ec3fcd816c1a.php
│   │       ├── 866362401f582754698240b21435c2ec.php
│   │       ├── 8838df62b6b67a0e15fef74c484e0acc.php
│   │       ├── 887f3323e98201b27cb4b69e0aad4d11.php
│   │       ├── 8b0ec4fdf1389b6f5db2f39aadbb03e6.php
│   │       ├── 8b77322ea40de4645844c2e3622c3810.php
│   │       ├── 8bd7b96f9b56b8dfb5ff24f4aa82a8af.php
│   │       ├── 8c2da46ec4c83d4f3a4a8806fe1bffb0.php
│   │       ├── 8cc63af9f776a5ce727ba354b71a028b.php
│   │       ├── 8d82578c6b7f2296eff92bdd034ec3f8.php
│   │       ├── 8e9ec212b56308113621678f9c2bd239.php
│   │       ├── 8edaeca2b67912849b0fa6afc6e9b59a.php
│   │       ├── 92b723efff48ff59a4baed38fab1e075.php
│   │       ├── 92d1ba7863a69db567fce33fa74ae8e4.php
│   │       ├── 9340e3fbacd8e8ef7b61ab6c923195d7.php
│   │       ├── 959a48f5e1777a006b4f72469e0c5823.php
│   │       ├── 95e3164b72bdaa6d9137dfc0a56b4aae.php
│   │       ├── 9745f6a6f3fcc1ddd95648c9a006bc71.php
│   │       ├── 980f0f903daf6976116b4dfe2001138f.php
│   │       ├── 983b248083d94f913aa9c643fc886a1b.php
│   │       ├── 9a1e6380fbc0a88463748eb89e6329b3.php
│   │       ├── 9b2441c2e03624cdf057248af1350b1e.php
│   │       ├── 9d34b53449eb84f7290aec7aea0c0293.php
│   │       ├── 9f09247e39996cdc52f6b1a72085d838.php
│   │       ├── 9f6a3c41d661c8a497a53550c8dfeca6.php
│   │       ├── 9f97c94c28c273c63f935aba5f563b13.php
│   │       ├── a32eabd06493dc44bab1d2b8a609b9ef.php
│   │       ├── a5e39d1248e6b1ed8f3c6cb5ae30e9ba.php
│   │       ├── a8304b448ab0b5f3baa16ad7262defeb.php
│   │       ├── aaf0cf4b15765a5ac94d61533c9148fb.php
│   │       ├── ac4c02a786db2cf28ea74e9136b850c9.php
│   │       ├── ae055b2eba5a3b335ef6e058a3c5c432.php
│   │       ├── b0160d9ee887b000436a549b742bcf73.php
│   │       ├── b05945dedfc5b152011406a46f537581.php
│   │       ├── b3799b1d63f5c5419f37c5226ddc5976.php
│   │       ├── b4eeef84afdcd894487f75a56720a836.php
│   │       ├── b5d67c3e92723123a61340510941eb19.php
│   │       ├── b5eb3e47c8442964aadddfc150c59a66.php
│   │       ├── b7c6ffb2f1a38946c8b71d5247809c35.php
│   │       ├── ba024b806b6790ad5fe67d939a734032.php
│   │       ├── bae129cef9e600352d1c88ca55b5c61c.php
│   │       ├── bb5b8ce07613652d7c82b1fb53a79808.php
│   │       ├── bff06be78bce3fed83e9c65b8001e4b4.php
│   │       ├── c006fb974db4b32c9bd1918fe50c28e8.php
│   │       ├── c00b680cd776799488cd993c98592c71.php
│   │       ├── c195c738068bafc6cde92a92b5ccb5e8.php
│   │       ├── c1e636c8be626d5a40b6f99e760b156f.php
│   │       ├── c2dd3723ff23c512fb666224019399dd.php
│   │       ├── c34c80fb4340d606152d20f04659bffa.php
│   │       ├── c46954486d662f19e200785444b4726a.php
│   │       ├── c597eabc01b3b531b9dda87258a1b39e.php
│   │       ├── c5efa7739cb6a786d74538810895e16b.php
│   │       ├── c71b992684525e8843a4a05ed7c7747a.php
│   │       ├── c728cfb0bd031cc1d24c7deb34ee86c2.php
│   │       ├── c76c0f1df0c15ca717690212e6ae669d.php
│   │       ├── c80aa879a3c3eebdfc4b9213caad5f6f.php
│   │       ├── cacad9aa301188bb671ec61542d90cdf.php
│   │       ├── cb9aac3c63f2d6f59db4f819b83779fd.php
│   │       ├── ccc6cbea740e737771cf097d87cca7c3.php
│   │       ├── cd9d05dcacd38af2741b53e712d9ba3e.php
│   │       ├── cf31866bb2d600c2ef2bb5d4d737c564.php
│   │       ├── cfb3b0d08523933b25d0e9e498df3235.php
│   │       ├── d02f3a98ba57405ca822d2f5bb49f0f7.php
│   │       ├── d0961b79c3235a49f72ee29dcc1c965c.php
│   │       ├── d1f36e3de69f0a26360ce06ac4b749a6.php
│   │       ├── d2b55f55e9cde2fc70996efa8b2b2200.php
│   │       ├── d3f1213af7b5447fc6561597d8570ea6.php
│   │       ├── d4085960d213b4c228d4dee8f4a8352c.php
│   │       ├── d42cfb8e5b4c95899361eaec4d9a5e3a.php
│   │       ├── d4f72208810fb08b6282269dededcbe6.php
│   │       ├── d5356ea8d103ee0f80e4798a71287fe4.php
│   │       ├── d54b4b1eb6bf70ee3b6a52e36ce7e503.php
│   │       ├── d732708c0cff75e271d4af88e873b0da.php
│   │       ├── d7f537f83e07733dd20902e2a349a47f.php
│   │       ├── d8023e53ca38edd3353749f456935467.php
│   │       ├── d8ed9ae43ac24a9b9d38ea23caf7d1e1.php
│   │       ├── da13e4910700019510cfc069c72ef5e9.php
│   │       ├── dcd2676c704182d2c2c3ef87bb2ca6aa.php
│   │       ├── dd310000961f2d208873a737c27d849a.php
│   │       ├── e003216ba244e86e190eeeb50d67f31d.php
│   │       ├── e0b5963289bb9a62e9b9792d4141be6d.php
│   │       ├── e31ec0cd99cab03458d3b62f87656f41.php
│   │       ├── e55b786daa13e08e15fb4dd4a576a4ea.php
│   │       ├── ebe1f9110e67a20ce7af713e78dbbc8a.php
│   │       ├── ed53e6526bfb3c939b903194d34698dc.php
│   │       ├── ed6d86ec5660851da9f9a0dad3f4f308.php
│   │       ├── ef4509c2024e5d00f432a9b5127f0da1.php
│   │       ├── ef6396e6be8f828bbec38436a4006945.php
│   │       ├── ef786feaac52b0fe713445889028f12a.php
│   │       ├── f15c9011e15e0d9039803e8824d2687d.php
│   │       ├── f188496d5460aa7137f13bb1da4b2db1.php
│   │       ├── f2ea235a16bc0ed1bd7a71a5dac35027.php
│   │       ├── f3511794a630a1667a46d420796cefad.php
│   │       ├── f497199d26d29b5bb4d0007bbb834737.php
│   │       ├── f509223acaba9c922d20d15875b1ded4.php
│   │       ├── f8df26867a6d8d49e7b9496f5ab8d6df.php
│   │       ├── f8e6e1b03aca4ea7357056687efd1904.php
│   │       ├── f905b6dee768cbfc0ad33952a263d185.php
│   │       ├── f9e3c437e6acde50e559801ad837aa9f.php
│   │       ├── fc880e268c72737a055acbb43b220152.php
│   │       ├── fdde25d26c98fa07ba90410fcaa1553c.php
│   │       ├── fee6c41ed3cc0c3cf1b467bf9f9bd5fb.php
│   │       └── fff0038203fd493765625b454948159b.php
│   ├── logs/
│   │   └── laravel.log
└── tests/
    ├── TestCase.php
    ├── Feature/
    │   └── ExampleTest.php
    └── Unit/
        └── ExampleTest.php
```

## Descripción de directorios principales

- **app/**: Código fuente de la aplicación Laravel
- **config/**: Archivos de configuración
- **database/**: Migraciones y seeders
- **resources/**: Vistas, CSS y JavaScript
- **routes/**: Definición de rutas
- **storage/**: Almacenamiento de archivos
- **tests/**: Tests automatizados
- **novex-docs/**: Documentación del proyecto con Docusaurus
- **docker/**: Configuración de Docker

# Concetps teoricos

**Controller**

- Es la capa que recibe la petición HTTP y devuelve una respuesta.
  Normalmente agrupa varias acciones relacionadas con un recurso o módulo: index, show, store, update, destroy. Laravel documenta los controllers como clases para organizar la lógica de manejo de requests, normalmente en app/Http/Controllers.

**Action**

- En Laravel, “action” puede significar dos cosas:
  Un método dentro de un controller (store, update, etc.).
  Un patrón muy usado en la comunidad: una clase con una sola responsabilidad, por ejemplo CreateUserAction, para encapsular una tarea concreta. Ese enfoque no es una pieza nativa central del framework como los controllers; suele venir de un patrón de arquitectura o de paquetes como Laravel Actions, que define las actions como clases que se encargan de una tarea específica.

**Diferencia conceptual**

- Controller = coordinador de entrada/salida HTTP
- Action = caso de uso o tarea de negocio puntual

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('stock') || ! Schema::hasColumn('stock', 'id_lote')) {
            return;
        }

        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            if (! $this->indexExists('stock', 'stock_id_producto_index')) {
                DB::statement('ALTER TABLE stock ADD INDEX stock_id_producto_index (id_producto)');
            }

            if (! $this->indexExists('stock', 'stock_id_ubicacion_index')) {
                DB::statement('ALTER TABLE stock ADD INDEX stock_id_ubicacion_index (id_ubicacion)');
            }

            if (! $this->indexExists('stock', 'stock_id_lote_index')) {
                DB::statement('ALTER TABLE stock ADD INDEX stock_id_lote_index (id_lote)');
            }

            DB::statement('ALTER TABLE stock DROP PRIMARY KEY');
            if (! $this->indexExists('stock', 'stock_producto_ubicacion_lote_unique')) {
                DB::statement('ALTER TABLE stock ADD UNIQUE stock_producto_ubicacion_lote_unique (id_producto, id_ubicacion, id_lote)');
            }

            return;
        }

        Schema::table('stock', function ($table) {
            $table->dropPrimary(['id_producto', 'id_ubicacion']);
            $table->unique(['id_producto', 'id_ubicacion', 'id_lote'], 'stock_producto_ubicacion_lote_unique');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('stock')) {
            return;
        }

        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE stock DROP INDEX stock_producto_ubicacion_lote_unique');
            DB::statement('ALTER TABLE stock ADD PRIMARY KEY (id_producto, id_ubicacion)');

            return;
        }

        Schema::table('stock', function ($table) {
            $table->dropUnique('stock_producto_ubicacion_lote_unique');
            $table->primary(['id_producto', 'id_ubicacion']);
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        $database = DB::connection()->getDatabaseName();

        return ! empty(DB::select(
            'select 1 from information_schema.statistics where table_schema = ? and table_name = ? and index_name = ? limit 1',
            [$database, $table, $index]
        ));
    }
};

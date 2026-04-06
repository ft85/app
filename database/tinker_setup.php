<?php
/**
 * Run this on cPanel via:
 *   php artisan tinker --execute="$(cat database/tinker_setup.php)"
 *
 * Or copy-paste into: php artisan tinker
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

// ── 1. unspec table ────────────────────────────────────────
if (!Schema::hasTable('unspec')) {
    Schema::create('unspec', function (Blueprint $table) {
        $table->increments('id');
        $table->string('unspec_code')->unique();
        $table->string('item_class');
        $table->timestamps();
    });
    echo "✓ unspec table created\n";
} else {
    echo "- unspec table already exists\n";
}

// ── 2. unspec + printer_id columns on categories ──────────
if (!Schema::hasColumn('categories', 'unspec')) {
    Schema::table('categories', function (Blueprint $table) {
        $table->string('unspec')->nullable()->after('description');
    });
    echo "✓ unspec column added to categories\n";
} else {
    echo "- categories.unspec already exists\n";
}

if (!Schema::hasColumn('categories', 'printer_id')) {
    Schema::table('categories', function (Blueprint $table) {
        $table->unsignedInteger('printer_id')->nullable()->after('unspec');
    });
    echo "✓ printer_id column added to categories\n";
} else {
    echo "- categories.printer_id already exists\n";
}

// ── 3. Seed OBR item class codes ──────────────────────────
$codes = [
    ['A1001', 'Boissons alcoolisees'],
    ['A1002', 'Boissons non alcoolisees'],
    ['A1003', 'Eaux minerales et eaux gazeuses'],
    ['B2001', 'Produits alimentaires et epicerie'],
    ['B2002', 'Produits laitiers'],
    ['B2003', 'Viandes et charcuteries'],
    ['B2004', 'Poissons et fruits de mer'],
    ['B2005', 'Pain, patisseries et cereales'],
    ['B2006', 'Fruits et legumes'],
    ['B2007', 'Huiles et graisses alimentaires'],
    ['B2008', 'Condiments, epices et sauces'],
    ['B2009', 'Sucre, confiseries et chocolat'],
    ['C3001', 'Tabac et produits du tabac'],
    ['D4001', 'Medicaments et produits pharmaceutiques'],
    ['D4002', 'Materiel et equipements medicaux'],
    ['D4003', 'Produits de sante et cosmetiques'],
    ['E5001', 'Vetements et textiles'],
    ['E5002', 'Chaussures et maroquinerie'],
    ['E5003', 'Accessoires vestimentaires'],
    ['F6001', 'Electronique grand public'],
    ['F6002', 'Telephones et accessoires'],
    ['F6003', 'Informatique et peripheriques'],
    ['F6004', 'Appareils electromenagers'],
    ['G7001', 'Materiaux de construction'],
    ['G7002', 'Quincaillerie et outillage'],
    ['G7003', 'Peintures et revetements'],
    ['H8001', 'Meubles et ameublement'],
    ['H8002', 'Literie et textiles maison'],
    ['H8003', 'Articles de menage et vaisselle'],
    ['I9001', 'Carburants et lubrifiants'],
    ['I9002', 'Pieces automobiles et accessoires'],
    ['J1001', 'Services de restauration'],
    ['J1002', 'Services hoteliers et hebergement'],
    ['J1003', 'Services de transport'],
    ['J1004', 'Services de telecommunication'],
    ['J1005', 'Services informatiques et technologie'],
    ['J1006', 'Services financiers et bancaires'],
    ['J1007', 'Services de sante et medicaux'],
    ['J1008', 'Services educatifs et formation'],
    ['J1009', 'Services de construction et genie civil'],
    ['J1010', 'Services de securite'],
    ['J1011', 'Services de nettoyage et entretien'],
    ['J1012', 'Services juridiques et comptables'],
    ['K1101', 'Papeterie et fournitures de bureau'],
    ['K1102', 'Livres, journaux et publications'],
    ['L1201', 'Produits agricoles et semences'],
    ['L1202', 'Engrais et produits phytosanitaires'],
    ['L1203', 'Animaux et produits elevage'],
    ['M1301', 'Equipements industriels et machines'],
    ['M1302', 'Matieres premieres industrielles'],
    ['N1401', 'Jouets et articles de sport'],
    ['N1402', 'Articles de loisirs et culture'],
    ['O1501', 'Bijoux et montres'],
    ['O1502', 'Produits de luxe'],
    ['P1601', 'Energie electrique'],
    ['P1602', 'Gaz et energie'],
    ['Q1701', 'Autres produits non classes'],
];

$inserted = 0;
foreach ($codes as [$code, $class]) {
    $exists = DB::table('unspec')->where('unspec_code', $code)->exists();
    if (!$exists) {
        DB::table('unspec')->insert([
            'unspec_code' => $code,
            'item_class'  => $class,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        $inserted++;
    }
}
echo "✓ Seeded {$inserted} OBR item class codes (" . DB::table('unspec')->count() . " total)\n";

// ── 4. injonge_code on products ────────────────────────────
if (!Schema::hasColumn('products', 'injonge_code')) {
    Schema::table('products', function (Blueprint $table) {
        $table->string('injonge_code')->nullable()->after('sku');
    });
    echo "✓ injonge_code column added to products\n";
} else {
    echo "- products.injonge_code already exists\n";
}

// ── 5. res_tables columns for table management ─────────────
if (!Schema::hasColumn('res_tables', 'assigned_waiter_id')) {
    Schema::table('res_tables', function (Blueprint $table) {
        $table->unsignedInteger('assigned_waiter_id')->nullable()->after('id');
    });
    echo "✓ assigned_waiter_id added to res_tables\n";
} else {
    echo "- res_tables.assigned_waiter_id already exists\n";
}

if (!Schema::hasColumn('res_tables', 'is_table_open')) {
    Schema::table('res_tables', function (Blueprint $table) {
        $table->tinyInteger('is_table_open')->default(0)->after('assigned_waiter_id');
    });
    echo "✓ is_table_open added to res_tables\n";
} else {
    echo "- res_tables.is_table_open already exists\n";
}

echo "\n✅ All done!\n";

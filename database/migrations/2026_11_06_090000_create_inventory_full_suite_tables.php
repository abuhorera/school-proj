<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Alter inventory_categories
        if (Schema::hasTable('inventory_categories')) {
            Schema::table('inventory_categories', function (Blueprint $table) {
                if (!Schema::hasColumn('inventory_categories', 'type')) {
                    $table->string('type')->default('Consumable'); // Consumable, Saleable, Asset
                }
                if (!Schema::hasColumn('inventory_categories', 'icon')) {
                    $table->string('icon')->nullable();
                }
                if (!Schema::hasColumn('inventory_categories', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
            });
        }

        // 2. Alter inventory_suppliers
        if (Schema::hasTable('inventory_suppliers')) {
            Schema::table('inventory_suppliers', function (Blueprint $table) {
                if (!Schema::hasColumn('inventory_suppliers', 'supplier_type')) {
                    $table->string('supplier_type')->default('General');
                }
                if (!Schema::hasColumn('inventory_suppliers', 'gstin')) {
                    $table->string('gstin')->nullable();
                }
                if (!Schema::hasColumn('inventory_suppliers', 'status')) {
                    $table->string('status')->default('Active');
                }
            });
        }

        // 3. Alter inventory_items
        if (Schema::hasTable('inventory_items')) {
            Schema::table('inventory_items', function (Blueprint $table) {
                if (!Schema::hasColumn('inventory_items', 'sku')) {
                    $table->string('sku')->nullable();
                }
                if (!Schema::hasColumn('inventory_items', 'barcode')) {
                    $table->string('barcode')->nullable();
                }
                if (!Schema::hasColumn('inventory_items', 'item_type')) {
                    $table->string('item_type')->default('Consumable'); // Consumable, Saleable, Asset
                }
                if (!Schema::hasColumn('inventory_items', 'location')) {
                    $table->string('location')->nullable();
                }
                if (!Schema::hasColumn('inventory_items', 'minimum_stock_level')) {
                    $table->integer('minimum_stock_level')->default(5);
                }
                if (!Schema::hasColumn('inventory_items', 'selling_price')) {
                    $table->decimal('selling_price', 10, 2)->default(0.00);
                }
                if (!Schema::hasColumn('inventory_items', 'expiry_date')) {
                    $table->date('expiry_date')->nullable();
                }
                if (!Schema::hasColumn('inventory_items', 'description')) {
                    $table->text('description')->nullable();
                }
            });
        }

        // 4. Alter inventory_sales
        if (Schema::hasTable('inventory_sales')) {
            Schema::table('inventory_sales', function (Blueprint $table) {
                if (!Schema::hasColumn('inventory_sales', 'billing_mode')) {
                    $table->string('billing_mode')->default('school'); // school, vendor
                }
                if (!Schema::hasColumn('inventory_sales', 'vendor_id')) {
                    $table->foreignId('vendor_id')->nullable();
                }
                if (!Schema::hasColumn('inventory_sales', 'buyer_type')) {
                    $table->string('buyer_type')->default('Student'); // Student, Staff, Walk-in
                }
                if (!Schema::hasColumn('inventory_sales', 'buyer_id')) {
                    $table->unsignedBigInteger('buyer_id')->nullable();
                }
                if (!Schema::hasColumn('inventory_sales', 'buyer_name')) {
                    $table->string('buyer_name')->nullable();
                }
                if (!Schema::hasColumn('inventory_sales', 'subtotal')) {
                    $table->decimal('subtotal', 10, 2)->default(0.00);
                }
                if (!Schema::hasColumn('inventory_sales', 'discount_amount')) {
                    $table->decimal('discount_amount', 10, 2)->default(0.00);
                }
                if (!Schema::hasColumn('inventory_sales', 'tax_amount')) {
                    $table->decimal('tax_amount', 10, 2)->default(0.00);
                }
                if (!Schema::hasColumn('inventory_sales', 'net_amount')) {
                    $table->decimal('net_amount', 10, 2)->default(0.00);
                }
                if (!Schema::hasColumn('inventory_sales', 'payment_mode')) {
                    $table->string('payment_mode')->default('CASH');
                }
                if (!Schema::hasColumn('inventory_sales', 'payment_reference')) {
                    $table->string('payment_reference')->nullable();
                }
            });
        }

        // 5. Create inventory_sale_items
        if (!Schema::hasTable('inventory_sale_items')) {
            Schema::create('inventory_sale_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inventory_sale_id')->constrained('inventory_sales')->onDelete('cascade');
                $table->foreignId('inventory_item_id')->nullable()->constrained('inventory_items')->onDelete('set null');
                $table->string('item_name');
                $table->integer('quantity')->default(1);
                $table->decimal('unit_price', 10, 2)->default(0.00);
                $table->decimal('discount', 10, 2)->default(0.00);
                $table->decimal('tax_percent', 5, 2)->default(0.00);
                $table->decimal('line_total', 10, 2)->default(0.00);
                $table->timestamps();
            });
        }

        // 6. Create inventory_stock_logs
        if (!Schema::hasTable('inventory_stock_logs')) {
            Schema::create('inventory_stock_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('cascade');
                $table->foreignId('inventory_supplier_id')->nullable()->constrained('inventory_suppliers')->onDelete('set null');
                $table->string('type')->default('purchase'); // purchase, opening_stock, adjustment_in, adjustment_out, damaged, expired, recount
                $table->decimal('quantity', 10, 2);
                $table->decimal('unit_cost', 10, 2)->nullable();
                $table->decimal('total_cost', 10, 2)->nullable();
                $table->date('date');
                $table->string('performed_by')->default('Admin');
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        // 7. Create inventory_issued_items
        if (!Schema::hasTable('inventory_issued_items')) {
            Schema::create('inventory_issued_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('cascade');
                $table->string('issued_to_type'); // staff, student, department
                $table->unsignedBigInteger('issued_to_id')->nullable();
                $table->string('issued_to_name');
                $table->integer('quantity')->default(1);
                $table->date('issue_date');
                $table->date('return_date')->nullable();
                $table->string('status')->default('Issued'); // Issued, Returned, Lost, Damaged
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        // 8. Create inventory_purchase_orders
        if (!Schema::hasTable('inventory_purchase_orders')) {
            Schema::create('inventory_purchase_orders', function (Blueprint $table) {
                $table->id();
                $table->string('po_no')->unique();
                $table->foreignId('inventory_supplier_id')->constrained('inventory_suppliers')->onDelete('cascade');
                $table->date('order_date');
                $table->date('expected_delivery_date')->nullable();
                $table->string('status')->default('Draft'); // Draft, Approved, Received, Cancelled
                $table->text('terms')->nullable();
                $table->text('notes')->nullable();
                $table->decimal('subtotal', 10, 2)->default(0.00);
                $table->decimal('tax_amount', 10, 2)->default(0.00);
                $table->decimal('total_amount', 10, 2)->default(0.00);
                $table->string('created_by')->default('school admin');
                $table->string('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
            });
        }

        // 9. Create inventory_purchase_order_items
        if (!Schema::hasTable('inventory_purchase_order_items')) {
            Schema::create('inventory_purchase_order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inventory_purchase_order_id')->constrained('inventory_purchase_orders')->onDelete('cascade');
                $table->foreignId('inventory_item_id')->nullable()->constrained('inventory_items')->onDelete('set null');
                $table->string('item_name');
                $table->integer('ordered_quantity')->default(1);
                $table->integer('received_quantity')->default(0);
                $table->decimal('unit_cost', 10, 2)->default(0.00);
                $table->decimal('gst_percent', 5, 2)->default(0.00);
                $table->decimal('line_total', 10, 2)->default(0.00);
                $table->timestamps();
            });
        }

        // 10. Create inventory_goods_receipts
        if (!Schema::hasTable('inventory_goods_receipts')) {
            Schema::create('inventory_goods_receipts', function (Blueprint $table) {
                $table->id();
                $table->string('grn_no')->unique();
                $table->foreignId('inventory_purchase_order_id')->nullable()->constrained('inventory_purchase_orders')->onDelete('set null');
                $table->foreignId('inventory_supplier_id')->constrained('inventory_suppliers')->onDelete('cascade');
                $table->date('received_date');
                $table->string('received_by')->default('school admin');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 11. Create inventory_goods_receipt_items
        if (!Schema::hasTable('inventory_goods_receipt_items')) {
            Schema::create('inventory_goods_receipt_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inventory_goods_receipt_id')->constrained('inventory_goods_receipts')->onDelete('cascade');
                $table->foreignId('inventory_item_id')->nullable()->constrained('inventory_items')->onDelete('set null');
                $table->string('item_name')->nullable();
                $table->integer('received_quantity')->default(0);
                $table->integer('rejected_quantity')->default(0);
                $table->string('rejection_reason')->nullable();
                $table->timestamps();
            });
        }

        // 12. Create inventory_supplier_payments
        if (!Schema::hasTable('inventory_supplier_payments')) {
            Schema::create('inventory_supplier_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inventory_supplier_id')->constrained('inventory_suppliers')->onDelete('cascade');
                $table->foreignId('inventory_purchase_order_id')->nullable()->constrained('inventory_purchase_orders')->onDelete('set null');
                $table->decimal('amount', 10, 2)->default(0.00);
                $table->date('payment_date');
                $table->string('payment_mode')->default('Cash'); // Cash, Bank Transfer, Cheque, UPI
                $table->string('reference_number')->nullable();
                $table->text('notes')->nullable();
                $table->string('created_by')->default('school admin');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_supplier_payments');
        Schema::dropIfExists('inventory_goods_receipt_items');
        Schema::dropIfExists('inventory_goods_receipts');
        Schema::dropIfExists('inventory_purchase_order_items');
        Schema::dropIfExists('inventory_purchase_orders');
        Schema::dropIfExists('inventory_issued_items');
        Schema::dropIfExists('inventory_stock_logs');
        Schema::dropIfExists('inventory_sale_items');
    }
};


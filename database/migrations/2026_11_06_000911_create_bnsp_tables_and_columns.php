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
        // 1. Categories table
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }

        // 2. Add columns to books table
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('id')->constrained('categories')->nullOnDelete();
            }
            if (!Schema::hasColumn('books', 'publisher')) {
                $table->string('publisher')->nullable()->after('author');
            }
            if (!Schema::hasColumn('books', 'description')) {
                $table->text('description')->nullable()->after('synopsis');
            }
            if (!Schema::hasColumn('books', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('cover_url');
            }
        });

        // 3. Stocks table (1:1 with books)
        if (!Schema::hasTable('stocks')) {
            Schema::create('stocks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('book_id')->unique()->constrained('books')->cascadeOnDelete();
                $table->integer('quantity')->default(10);
                $table->timestamps();
            });
        }

        // 4. Carts table
        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
                $table->integer('quantity')->default(1);
                $table->timestamps();
            });
        }

        // 5. Update orders table with order_code, shipping_address
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'order_code')) {
                $table->string('order_code')->nullable()->after('id');
            }
            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->after('payment_method');
            }
        });

        // 6. Order Items table
        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
                $table->integer('quantity')->default(1);
                $table->integer('price');
                $table->timestamps();
            });
        }

        // 7. Contacts table
        if (!Schema::hasTable('contacts')) {
            Schema::create('contacts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('name');
                $table->string('email');
                $table->text('message');
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('stocks');

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'order_code')) {
                $table->dropColumn('order_code');
            }
            if (Schema::hasColumn('orders', 'shipping_address')) {
                $table->dropColumn('shipping_address');
            }
        });

        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
            if (Schema::hasColumn('books', 'publisher')) {
                $table->dropColumn('publisher');
            }
            if (Schema::hasColumn('books', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('books', 'cover_image')) {
                $table->dropColumn('cover_image');
            }
        });

        Schema::dropIfExists('categories');
    }
};

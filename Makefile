# ========== 共通 ==========
build-no-cache:
	docker compose build --no-cache

build:
	docker compose build

up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose restart

logs:
	docker compose logs -f

login:
	docker compose exec ec-cube bash

cache-clear:
	docker compose exec ec-cube bash -lc "php bin/console cache:clear"


# ========== 本番環境 (prod) ==========
prod-build:
	docker compose -f docker-compose.yml build --no-cache

prod-up:
	docker compose -f docker-compose.yml up -d --build --no-cache

prod-down:
	docker compose -f docker-compose.yml down

prod-restart:
	docker compose -f docker-compose.yml restart

prod-logs:
	docker compose -f docker-compose.yml logs -f

prod-login:
	docker compose -f docker-compose.yml exec ec-cube bash

prod-cache-clear:
	docker compose -f docker-compose.yml exec ec-cube bash -lc "php bin/console cache:clear"




# ========== EC-CUBE DB Utility ==========

# DBコンテナ名（docker-compose.ymlのサービス名と一致させる）
DB_CONTAINER=db
APP_CONTAINER=ec-cube

# SQLファイルのパス
SQL_FILE=tmp/eccube_data_core.sql

# ========================================

## DBを完全初期化してマスタデータを再生成
delete-core-data:
	docker compose exec $(APP_CONTAINER) bash -lc "\
		php bin/console doctrine:database:drop --force && \
		php bin/console doctrine:database:create && \
		php bin/console doctrine:schema:create && \
		php bin/console eccube:fixtures:load \
	"

## ダンプしたコアデータを流し込む
TABLES = dtb_base_info dtb_block dtb_category dtb_class_category dtb_class_name dtb_csv \
dtb_customer dtb_customer_address dtb_customer_favorite_product dtb_delivery \
dtb_delivery_duration dtb_delivery_fee dtb_delivery_time dtb_layout dtb_mail_template \
dtb_news dtb_order dtb_order_item dtb_page dtb_page_layout dtb_payment dtb_payment_option \
dtb_product dtb_product_category dtb_product_class dtb_product_image \
dtb_product_stock dtb_product_tag dtb_shipping dtb_tag dtb_tax_rule dtb_block_position

insert-core-data:
	@echo "🧹 Truncating tables..."
	docker compose exec -T db mysql -u eccube -peccube eccube -e "SET FOREIGN_KEY_CHECKS=0; $(foreach T,$(TABLES),TRUNCATE TABLE $(T);) SET FOREIGN_KEY_CHECKS=1;"
	@echo "📥 Importing core data from tmp/eccube_data_core.sql ..."
	cat tmp/eccube_data_core.sql | docker compose exec -T db mysql -u eccube -peccube eccube


# ========================================
# EC-CUBE プラグイン一括インストール
# ========================================

PLUGIN_CODES = Api42 Coupon42 MailMagazine42 ProductReview42 Recommend42 RelatedProduct42 SalesReport42 Securitychecker42 SiteKit42

plugin-install:
	@docker compose exec ec-cube bash -lc '\
	for p in $(PLUGIN_CODES); do \
		echo "🔧 Installing and enabling $$p ..."; \
		bin/console eccube:plugin:install --code=$$p --if-not-exists || true; \
	done; \
	echo "✅ All plugins installed."'

plugin-enable:
	@docker compose exec ec-cube bash -lc '\
	for p in $(PLUGIN_CODES); do \
		echo "🔧 Installing and enabling $$p ..."; \
		bin/console eccube:plugin:enable --code=$$p || true; \
	done; \
	echo "✅ All plugins enabled."'

plugin-setup:
	@docker compose exec ec-cube bash -lc '\
	for p in $(PLUGIN_CODES); do \
		echo "🔧 Installing and enabling $$p ..."; \
		bin/console eccube:plugin:install --code=$$p --if-not-exists || true; \
		bin/console eccube:plugin:enable --code=$$p || true; \
	done; \
	echo "✅ All plugins installed and enabled."'
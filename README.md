# PHP SDK for SaaSus Platform

[English page is here.](./README_en.md)

---

## SDK 利用の準備

### セットアップ

```
composer config repositories.saasus-platform/saasus-sdk-php vcs https://github.com/saasus-platform/saasus-sdk-php
```

### SDK 追加

```
composer require saasus-platform/saasus-sdk-php
```

### 環境変数を定義

```ini
### for SaaSus Platform
SAASUS_SAAS_ID="（画面のSaaS ID）"
SAASUS_API_KEY="（画面のAPI KEY）"
SAASUS_SECRET_KEY="（画面のクライアントシークレット）"
```

SAASUS_SAAS_ID, SAASUS_API_KEY, SAASUS_SECRET_KEY は SaaS 開発コンソール画面に表示されている SaaS ID、 API キー 　と　クライアントシークレットを設定します。

SDK はこれらを `getenv()` で読み込みます。素の PHP は `.env` ファイルを自動では読み込まないので、`.env` を使う場合は `getenv()` から見える形で読み込んでください（例: vlucas/phpdotenv なら `Dotenv::createUnsafeImmutable()`）。

### 動作要件

- PHP 8.4 以上（PHP 8.4 / 8.5 で動作確認）
- フレームワーク非依存（Laravel 不要）

### 利用例

```php
use AntiPatternInc\Saasus\Api\Client;

// 環境変数 SAASUS_SAAS_ID / SAASUS_API_KEY / SAASUS_SECRET_KEY を読み込みます
$client = new Client();
$userInfo = $client->getAuthClient()->getUserInfo(['token' => $idToken]);
```

> 以前同梱していた Laravel 用 Middleware / Controller（`AntiPatternInc\Saasus\Laravel\*`）はコアから削除しました。

---

## PHP SDK

- [Auth](./generated/Auth/README.md)

  ユーザ情報、基本情報、認証情報、テナント情報、役割(ロール)情報などに参照・更新に利用します。

- [Pricing](./generated/Pricing/README.md)

  プライシングユニット、機能メニュー、料金プラン、メータリングユニットカウントなど料金に関連する情報の参照・更新に利用します。

- [Billing](./generated/Billing/README.md)

  請求業務で使う外部 SaaS との連携情報の参照・更新に利用します。

- [Integration](./generated/Integration/README.md)

  EventBridge 連携設定の参照・更新に利用します。

- [AwsMarketplace](./generated/AwsMarketplace/README.md)

  AWS Marketplace 連携設定の参照・更新に利用します。

- [Communication](./generated/Communication/README.md)

  ユーザーからフィードバックを収集したり、回答するプラットフォームの作成に利用します。

- [ApiLog](./generated/ApiLog/README.md)

  SaaSus Platformで提供しているAPIを実行した際のログ履歴の確認に利用します。
  
---

## ユースケースサンプル

準備中・・・

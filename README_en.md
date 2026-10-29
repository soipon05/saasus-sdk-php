# PHP SDK for SaaSus Platform

[Japanese page is here.](./README.md)

---

## Preparing to use the SDK

### Set Up

```
composer config repositories.saasus-platform/saasus-sdk-php vcs https://github.com/saasus-platform/saasus-sdk-php
```

### Added SDK

```
composer require saasus-platform/saasus-sdk-php
```

### Define Environment Variables

```ini
### for SaaSus Platform
SAASUS_SAAS_ID="(SaaS ID on Screen)"
SAASUS_API_KEY="(API KEY on Screen)"
SAASUS_SECRET_KEY="(Client Secret on Screen)"
SAASUS_LOGIN_URL="https://auth.sample.saasus.jp/ (Login Screen URL)"
```

SAASUS_SAAS_ID, SAASUS_API_KEY, SAASUS_SECRET_KEY are the SaaS ID, API key and client secret displayed on the SaaS development console screen,
SAASUS_LOGIN_URL sets the URL of the login screen created in the SaaS development console.

### Requirements

- PHP 8.4 or later (tested on PHP 8.4 / 8.5)
- Framework-agnostic (no Laravel required)

### Usage

```php
use AntiPatternInc\Saasus\Api\Client;

// Reads SAASUS_SAAS_ID / SAASUS_API_KEY / SAASUS_SECRET_KEY from the environment
$client = new Client();
$userInfo = $client->getAuthClient()->getUserInfo(['token' => $idToken]);
```

> The Laravel middleware / controllers previously bundled (`AntiPatternInc\Saasus\Laravel\*`) have been removed from the core package.

---

## PHP SDK

- [Auth](./generated/Auth/README_en.md)

  It is used for referencing/updating user information, basic information, authentication information, tenant information, role information, etc.

- [Pricing](./generated/Pricing/README_en.md)

  It is used to refer to and update information related to charges, such as pricing units, function menus, charge plans, and metering unit counts.

- [Billing](./generated/Billing/README_en.md)

  It is used for referencing/updating information related to external SaaS used in billing operations.

- [Integration](./generated/Integration/README_en.md)

  It is used for referencing/updating information related to Amazon EventBridge.

- [AwsMarketplace](./generated/AwsMarketplace/README_en.md)

  It is used for referencing/updating information related to AWS Marketplace.

- [Communication](./generated/Communication/README.md)

  It is used to create a responsive platform for handling user feedback.

- [ApiLog](./generated/ApiLog/README.md)

  It is used to check the log history when executing the API provided by SaaSus Platform.

---

## Use Case Sample

In Preparation···

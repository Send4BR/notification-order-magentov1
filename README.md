# Order Notification :loudspeaker:

### Webhook module magento to order notification created or updated

# Installation

## Using Modman

- Make sure you have [Modman](https://github.com/colinmollenhour/modman) installed
- Allow symlinks for the templates directory (required for installations via Modman)
    - Use n98-magerun like pro: `n98-magerun.phar dev:symlinks`
    - Or just set 'Allow Symlinks' to 'Yes' from System - Configuration / Advanced / Developer / Template Settings

- Install Notification Order module:
```bash
cd [magento root folder]
modman init
modman clone https://github.com/Send4BR/notification-order-magentov1.git
```
- Flush Magento's cache

## Manual

- Download project
- Copy folder Aftersale and paste in your project in app/code/community
- Copy file Aftersale_NotificationOrder.xml and paste in app/etc/modules

### How to update
```
cd [magento root folder]
modman update magneto-debug
```
- Flush Magento's cache

# Configuration

### You must configure ecommerce uiid and url webhook in configuration admin ORDER WEBHOOK

![Alt text](./assets/module_webhook.png?raw=true "Title")



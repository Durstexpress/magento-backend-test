# Durstexpress Magento Test

### Your task: 
0. Setup environment as described in next section ( see [setup](#setup))

1. Create a Magento module Dex_PriceCalculation.

2. Add to admin panel new admin configuration field under Dex module(Dex_PriceCalculation) backend config section that is saving integer value

3. Whenever user add product to a cart multiply it’s price with value saved in admin panel

4. (Optional) Add 50% discount to product price on checkout without using voucher.

5.(Good to have it) Add a Integration test for the Magento module Dex_PriceCalculation.

As result we are expecting to receive pull request with name in format <TEST-YOUR_NAME>. you should not spend more 
than 3-4 hours on the test it is not necessary to finish all of it. we want to evalute your knowledge related to Magento


### Setup

##### 1. Clone project
```bash
git clone git@github.com:Durstexpress/magento-backend-test.git
```
##### 2. run  `bin/start` and `bin/setup` and update run magento provision commands if needed

Prerequisites :This setup assumes you are running Docker 


##### 3. You are done. Now you can test service by url after setting up etc/hosts [https://magento2.test/](https://magento2.test/) 

 



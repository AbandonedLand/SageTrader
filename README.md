# SageTrader (Name will change)

## Purpose
This application works with the [Sage Wallet](https://github.com/xch-dev/sage) to make trading easier.

Unlike other crypto currencies, [Chia Network](https://chia.net) allows trading in a non-custodial way.  This accomplished though offer files.

Offer files let you broadcast what you're willing to trade out to the world in a safe and secure way.  You can create an offer in your wallet without paying any fees.  This is not like ethereum where you need to approve the trade, then execute the trade.  In Chia, you create 1/2 of a transaction. 

For Example:  If I wish to use [wUSDC.b](https://warp.green) ( Base Bridged USDC ) to buy XCH, I would create an offer file where I offer 11.5 wUSDC.b and Request 1 XCH.  Doing so creates a text string that looks like this.

This string can be imported into another chia wallet and accepted by anyone and the trade would complete without any more interaction from the creator.

``` 
offer1qqr83wcuu2rykcmqvps85yw7ngk2sx25n5680avehv0ralxnhghduaaa0e4yymwuueut8jvvpt4xv0dtreul88trn63fdx7mln2t8nwywak7ynufdycc5hwp6dtm6l7vqqrfm7lqah2drknlknmplt0aga4ll5w60760v8asmg04eje0xr0lww0pt4468udkhxg4zymj3lzr9ts0xz0mkd3sdenkp4lwrfvj6ur0uql78qjltcf9ep74nm2jm6kuj8ua3fffandh443zlxdf7f48vuewuk4k0hj4c6ymqxucyx7w3wuvqpn0dplzxmhg0nc869xlnx2snmmdn5pdtmmkxg58lha72f7vlg0ewge72w0nf20r7wxtwk2jt03ccyla9g9lr5mqqzpjqpkwullmlu969masv7n9cl6ulth0772hn6n03drfg78xv096w8thty5asz25mkh0u2r0lludluhn30llmmd338697aktn0e2as7wm0dacn98fa49vu9nktkw09l0lrlzuhnllxm5mm7z0tqvvjelrlc9dv8fck9s0efc2jthj2ccucw2jl9h853xnxhte5chxtnkf578tw859e07xlz4e7j0tqvmg80h0lqundcdqlh6a08ef9hkumaye0xth77wcskjhr50vguty7zfl096k008ujy8qlctgwf7lf5xlu9cyksmxsuw9ulyualmk2dx6gk900fd0tqa25hwhse6ly7t7arzl7x2lhll7qjzekcl7zcgxhy0udenwnhxkgx29zpyzpf3pg2pqrqyqzsnpq9frdke0le8yc9ju7r3chc2ad6z2dplja8wu2h39eg49lhxf9ga40nlh84f8mz74c0th4mcjjxplld3shjuty4vqszuvfha3cthev6k2la38arht2mrlp8mu4wwhgm36mwjt46asmnjfv7al5ltn54lgvnymxqelcgj80f82x94lx3h3mf66wfzwktvngjzymrmj85uae5gh8m05zm9vu7nztl67e6ezayese7dknl0t0fs7m8hn0v8d5jdcalswqqzmg627w2dpfl

```

## Why build this app?
I think that that user experience for offers is not as good as a centralized exchange.  I'm trying to improve that some.  I also believe that self custody is very important.  Being able to put assets to work and maintain control of those assets is huge.

## Should I trust your app?
I strongly suggest you don't trust any app that has direct access to your crypto wallet.  This app would have access to the sage rpc which in turn gives it access to your crypto.  
I'm making it open source to show people the code so there's a bit more trust in the app and that this will not steal your keys.  

## Will your app go though an audit?
I think that would be a great idea.  I am a team of one and this is day 2 of coding.  We're pretty far away from production ready code. That will be looked into more as v1 gets closer.

## Does the app take any fees?
Yes and No.  The app will never add fees (as in charges by the developer) to trades or transfers.  I plan to keep this open source.  I do use two different API's that have the ability to accept fees for sending offer's their way.  
Those apis are the [Dexie Swap API](https://dexie.space/api/swap) and the [TibetSwap Api](https://api.v2.tibetswap.io/docs#/default/create_offer_endpoint_offer__pair_id__post).  These are used to create market orders for a trading pair. 
There may be premium features added down the road.

## What is planned for the app? 

### BOTS

- Dollar Cost Average Bot
  - Set trading pair to auto market buy/sell over a time period.
- Grid Bot
  - Set a trading pair / range / step value to grid trade tokens.
- UniswapV3 Bot
  - A concentrated liquidity bot that functions similar to a grid bot, but uses more math.
- NFT Bot
  - when you want to buy any NFT from a collection.  This will let you put in an offer on every NFT in the collection if you want, but only allows 1 nft to actually be purchased.
- Silent Bot
  - Doesn't create an offer, but will occasionally check to see if there's a favorable price for a trading pair and will purchase it if the criteria is met. 
- Dutch Auction Bot
  - Create a dutch auction:  Set Starting Price, Duration, Price Drop Amount and Min price.  This will create an expiring offer for an NFT or cat pair, then drop the price at the defined interval until it's sold or the min price is reached and it stops.

### OTHER FEATURES
- An interface into Dexie and TibetSwap
- An Address Book for XCH Addresses.
- Order log (track all trades do though app)




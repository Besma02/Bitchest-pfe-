select total from cryptocurrencies,crypto_wallets
where crypto_wallets.idCrypto = cryptocurrencies.id

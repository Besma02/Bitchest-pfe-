<template>
  <div class="flex bg-lightGray p-5">
    <div class="trader">
        <h1>CryptoCurrency</h1>
        <p>Explore top cryptos like Bitcoin, Ethereum and Grow your portfolio automatically with daily, weekly, or monthly trades.</p>
         <button>See More</button>
    </div>
    <div class="grid-container">
  <div class="card">
     <img src="../../assets/cryptoImages/bitcoin.png" class="mx-auto"/>  
      <h3 class="text-neonGreen">Bitcoin</h3>
      <h4 >48.56 €</h4>
    </div>
  <div class="card">
    <img src="../../assets/cryptoImages/ethereum.png" class="mx-auto"/>  
      <h3 class="text-neonGreen">Ethereum</h3>
      <h4>38.56 €</h4>
  </div>
  <div class="card">
    <img src="../../assets/cryptoImages/ripple.png" class="mx-auto"/>  
      <h3 class="text-neonGreen">Ripple</h3>
      <h4>4.56 €</h4>
  </div>
  <div class="card">
    <img src="../../assets/cryptoImages/dash.png" class="mx-auto"/>  
      <h3 class="text-neonGreen">Dash</h3>
      <h4>20.56 €</h4>
  </div>
  <div class="card">
    <img src="../../assets/cryptoImages/cardano.png" class="mx-auto"/>  
      <h3 class="text-neonGreen">Cardano</h3>
      <h4>25.56 €</h4>
  </div>
  <div class="card">
    <img src="../../assets/cryptoImages/litecoin.png" class="mx-auto"/>  
      <h3 class="text-neonGreen">Litecoin</h3>
      <h4>14.56 €</h4>
  </div>
</div>
    <div>
     
    </div>
    
  </div>
</template>

<script>
export default {

}
</script>

<style>
.container{
    display: flex;
    background: #F8F8F8;
    padding: 20px;
   
   
}
.trader{
    width:433px ;
    height:385px ;
    margin-left: 168px;
    margin-right: 134px;
   
}
.trader h1{
    font-size: 48px;
    font-weight: bold;
    margin-top: 20px;
    margin-bottom: 30px;
    
} 
.trader p{
    font-size: 24px;
    line-height: 2.625rem;
    font-weight: 400px;
    color: #262626;
    
}

.trader button{
    font-size: 36px;
    border: none;
    font-weight: bold;
    border-radius: 20px;
    background: #01FF19;
    color: #ffff;
    padding: 5px 20px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    margin-top: 30px;
   
    
}
.grid-container {
  display: grid;
  grid-template-columns: repeat(3, 1fr); /* 3 colonnes */
  gap: 30px; 
  padding: 20px; 
}

.card {
  background-color: #ffff;
  width:190px ;
  height:170px ;
  border: 1px solid #ddd;
  border-radius: 33px;
  padding: 40px;
  text-align: center;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;

 
}
.card:hover {
    transform: scale(1.05); 
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2); 
}
.card h3{
    font-weight: bold;
    
}
</style>
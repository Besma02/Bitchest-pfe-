<template>
  <div class="flex flex-col lg:flex-row bg-lightGray p-4 sm:p-5 md:p-6 lg:p-5">
    <!-- Section de texte -->
    <div
      class="trader w-full lg:w-[413px] lg:h-[326px] lg:ml-[7rem] lg:mr-[7.625rem] mb-8 lg:mb-0"
    >
      <h1
        class="text-2xl sm:text-3xl md:text-[2.5rem] lg:text-[3rem] font-bold mt-4 mb-6 lg:mt-7 lg:mb-7"
      >
        CryptoCurrency
      </h1>
      <p
        class="text-sm sm:text-base md:text-lg lg:text-[1.5rem] leading-6 sm:leading-7 md:leading-[2rem] lg:leading-[2.625rem] font-normal text-[#262626] mb-6 lg:mb-[2.5rem]"
      >
        Explore top cryptos like Bitcoin, Ethereum and Grow your portfolio
        automatically with daily, weekly, or monthly trades.
      </p>
      <custom-button
        class="text-bitchest-white font-bold text-base sm:text-lg md:text-xl lg:text-3xl border-0 rounded-lg lg:rounded-[20px] bg-bitchest-success px-4 py-2 sm:w-[150px] md:w-[165px] lg:w-[185px] mx-auto lg:mx-0 shadow-md"
      >
        <span style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2)">See More</span>
      </custom-button>
    </div>

    <!-- Section des cartes -->
    <div
      class="grid grid-cols-2 sm:grid-cols-3 gap-4 sm:gap-6 md:gap-8 lg:gap-[3.75rem] lg:p-[1.25rem]"
    >
      <!-- Carte individuelle -->
      <div
        class="bg-bitchest-white w-full sm:w-[170px] md:w-[180px] lg:w-[190px] h-[170px] border border-gray-300 rounded-[2.0625rem] p-4 sm:p-[2rem] lg:p-[2.5rem] text-center shadow-md transition-transform duration-300 ease-in-out hover:scale-105 hover:shadow-lg"
      >
        <img src="../../assets/cryptoImages/bitcoin.png" class="mx-auto mb-4" />
        <h3 class="text-bitchest-success font-bold text-sm sm:text-base md:text-lg lg:text-xl">
          Bitcoin
        </h3>
        <h4 class="text-xs sm:text-sm md:text-base lg:text-lg">48.56 €</h4>
      </div>
      <div
        class="bg-bitchest-white w-full sm:w-[170px] md:w-[180px] lg:w-[190px] h-[170px] border border-gray-300 rounded-[2.0625rem] p-4 sm:p-[2rem] lg:p-[2.5rem] text-center shadow-md transition-transform duration-300 ease-in-out hover:scale-105 hover:shadow-lg"
      >
        <img src="../../assets/cryptoImages/ethereum.png" class="mx-auto mb-4" />
        <h3 class="text-bitchest-success font-bold text-sm sm:text-base md:text-lg lg:text-xl">
          Ethereum
        </h3>
        <h4 class="text-xs sm:text-sm md:text-base lg:text-lg">38.56 €</h4>
      </div>
      <div
        class="bg-bitchest-white w-full sm:w-[170px] md:w-[180px] lg:w-[190px] h-[170px] border border-gray-300 rounded-[2.0625rem] p-4 sm:p-[2rem] lg:p-[2.5rem] text-center shadow-md transition-transform duration-300 ease-in-out hover:scale-105 hover:shadow-lg"
      >
        <img src="../../assets/cryptoImages/ripple.png" class="mx-auto mb-4" />
        <h3 class="text-bitchest-success font-bold text-sm sm:text-base md:text-lg lg:text-xl">
          Ripple
        </h3>
        <h4 class="text-xs sm:text-sm md:text-base lg:text-lg">4.56 €</h4>
      </div>
      <div
        class="bg-bitchest-white w-full sm:w-[170px] md:w-[180px] lg:w-[190px] h-[170px] border border-gray-300 rounded-[2.0625rem] p-4 sm:p-[2rem] lg:p-[2.5rem] text-center shadow-md transition-transform duration-300 ease-in-out hover:scale-105 hover:shadow-lg"
      >
        <img src="../../assets/cryptoImages/dash.png" class="mx-auto mb-4" />
        <h3 class="text-bitchest-success font-bold text-sm sm:text-base md:text-lg lg:text-xl">
          Dash
        </h3>
        <h4 class="text-xs sm:text-sm md:text-base lg:text-lg">20.56 €</h4>
      </div>
      <div
        class="bg-bitchest-white w-full sm:w-[170px] md:w-[180px] lg:w-[190px] h-[170px] border border-gray-300 rounded-[2.0625rem] p-4 sm:p-[2rem] lg:p-[2.5rem] text-center shadow-md transition-transform duration-300 ease-in-out hover:scale-105 hover:shadow-lg"
      >
        <img src="../../assets/cryptoImages/cardano.png" class="mx-auto mb-4" />
        <h3 class="text-bitchest-success font-bold text-sm sm:text-base md:text-lg lg:text-xl">
          Cardano
        </h3>
        <h4 class="text-xs sm:text-sm md:text-base lg:text-lg">25.56 €</h4>
      </div>
      <div
        class="bg-bitchest-white w-full sm:w-[170px] md:w-[180px] lg:w-[190px] h-[170px] border border-gray-300 rounded-[2.0625rem] p-4 sm:p-[2rem] lg:p-[2.5rem] text-center shadow-md transition-transform duration-300 ease-in-out hover:scale-105 hover:shadow-lg"
      >
        <img src="../../assets/cryptoImages/litecoin.png" class="mx-auto mb-4" />
        <h3 class="text-bitchest-success font-bold text-sm sm:text-base md:text-lg lg:text-xl">
          Litecoin
        </h3>
        <h4 class="text-xs sm:text-sm md:text-base lg:text-lg">14.56 €</h4>
      </div>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style></style>

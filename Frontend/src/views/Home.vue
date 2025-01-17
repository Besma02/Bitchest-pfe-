<template>
  <header>
    <NavBar />
  </header>
  <main>
    <HomeSection />
    <AboutSection />
    <TradeSection />
    <ServicesSection />
  </main>
</template>

<script setup>
import NavBar from "@/components/NavBar.vue";
import HomeSection from "@/components/sections/HomeSection.vue";
import AboutSection from "@/components/sections/AboutSection.vue";
import TradeSection from "@/components/sections/TradeSection.vue";
import ServicesSection from "@/components/sections/ServicesSection.vue";
</script>
<style>
header {
  position: sticky;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
}
</style>

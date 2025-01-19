<template>
  <div>
    <header>
      <NavBar />
    </header>
    <main>
      <HomeSection/>
      <Trade />
    </main>
  </div>
</template>

<script>
import Trade from "../components/sections/Trade.vue";
import NavBar from "@/components/NavBar.vue";
import HomeSection from "@/components/sections/HomeSection.vue";
import AboutSection from "@/components/sections/AboutSection.vue";
import ServicesSection from "@/components/sections/ServicesSection.vue";

export default {
  components: {
    NavBar,
    HomeSection,
    Trade,
  },
};
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

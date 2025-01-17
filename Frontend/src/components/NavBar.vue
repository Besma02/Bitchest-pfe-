<template>
  <nav class="text-bitchest-black p-4 bg-bitchest-white">
    <div
      class="container mx-auto flex flex-col md:flex-row justify-between items-center"
    >
      <img
        src="@/assets/bitchest_logo.png"
        alt="Logo Bitchest"
        class="h-12 mr-4"
      />

      <!-- Bouton de menu pour petits écrans -->
      <button class="md:hidden text-2xl focus:outline-none" @click="toggleMenu">
        &#9776;
      </button>

      <!-- Menu des liens de navigation -->
      <div
        :class="[
          'md:flex md:flex-row md:space-x-10 items-center font-bitchest font-normal text-xl',
          'lg:flex lg:flex-row justify-between lg:space-x-10 items-center font-bitchest font-normal text-xl',
          isMenuOpen ? 'flex flex-col  w-full space-y-4 mt-4' : 'hidden',
        ]"
      >
        <a
          href="#home"
          :class="[
            'hover:text-bitchest-secondary',
            {
              'text-bitchest-secondary border-b-2 border-bitchest-secondary':
                activeSection === 'home',
            },
          ]"
        >
          Home
        </a>
        <a
          href="#about"
          :class="[
            'hover:text-bitchest-secondary',
            {
              'text-bitchest-secondary border-b-2 border-bitchest-secondary':
                activeSection === 'about',
            },
          ]"
        >
          About Us
        </a>
        <a
          href="#trade"
          :class="[
            'hover:text-bitchest-secondary',
            {
              'text-bitchest-secondary border-b-2 border-bitchest-secondary':
                activeSection === 'trade',
            },
          ]"
        >
          Trade
        </a>
        <a
          href="#services"
          :class="[
            'hover:text-bitchest-secondary',
            {
              'text-bitchest-secondary border-b-2 border-bitchest-secondary':
                activeSection === 'services',
            },
          ]"
        >
          Services
        </a>
        <CustomButton variant="primary" @click="handleSigninClick">
          Sign in
        </CustomButton>
      </div>
    </div>
  </nav>
</template>

<script>
import CustomButton from "@/components/CustomButton.vue";

export default {
  name: "NavBar",
  components: {
    CustomButton,
  },
  data() {
    return {
      isMenuOpen: false,
      activeSection: "home",
    };
  },
  mounted() {
    const sections = document.querySelectorAll("section");
    const observer = new IntersectionObserver(this.observeSections, {
      threshold: 0.7,
    });
    sections.forEach((section) => observer.observe(section));
  },
  methods: {
    toggleMenu() {
      this.isMenuOpen = !this.isMenuOpen;
    },
    handleSigninClick() {
      console.log("Signin button clicked");
    },
    observeSections(entries) {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          this.activeSection = entry.target.id;
        }
      });
    },
  },
};
</script>

<template>
  <button :class="buttonClass">
    <slot></slot>
  </button>
</template>

<script>
export default {
  name: "CustomButton",
  props: {
    variant: {
      type: String,
      default: "primary",
    },
  },
  computed: {
    buttonClass() {
      return {
        "bg-bitchest-secondary text-bitchest-white px-4 py-2 rounded hover:bg-bitchest-alert":
          this.variant === "primary",
        "bg-bitchest-primary text-bitchest-white px-4 py-2 rounded hover:bg-bitchest-secondary":
          this.variant === "secondary",
      };
    },
  },
};
</script>

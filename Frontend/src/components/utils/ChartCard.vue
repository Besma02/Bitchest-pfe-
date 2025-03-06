<template>
  <div class="bg-white p-4 rounded-lg shadow">
    <h3 class="text-lg text-bitchest-black font-medium mb-2">{{ title }}</h3>
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
  },
};
</script>

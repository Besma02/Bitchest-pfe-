<template>
  <input
    type="text"
    :placeholder="placeholder"
    class="border border-bitchest-black rounded-2xl p-2 mb-4 w-full md:w-auto"
  />
</template>

<script>
export default {
  name: "CustomInput",
  props: {
    placeholder: {
      type: String,
      default: "Your Text Here",
    },
  },
};
</script>

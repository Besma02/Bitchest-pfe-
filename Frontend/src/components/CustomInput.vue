<template>
  <input
    type="text"
    :placeholder="placeholder"
    :value="value"
    @input="inputValue"
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
    value: {
      // Prop 'value' qui est utilisée par v-model
      type: String,
      default: "",
    },
  },
  methods: {
    inputValue() {
      this.$emit("inputData", event.target.value);
    },
  },
};
</script>

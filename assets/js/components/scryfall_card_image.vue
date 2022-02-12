<template>
  <div class="text-center">
    <img :class="[$style['scryfall-image'], 'w-100']" :src="imageSrc" :alt="imageAlt">
  </div>
</template>

<script>
export default {
  name: "ScryfallCardImage",
  props: [
      'imageSrc',
      'imageAlt'
  ]
}
</script>

<style module>
  .scryfall-image {
    max-width: 336px;
  }
</style>
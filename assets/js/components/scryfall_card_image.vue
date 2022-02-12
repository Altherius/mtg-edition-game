<template>
  <div class="text-center">
    <div v-show="!loading">
      <img :class="[$style['scryfall-image'], 'w-100']" :src="imageSrc" :alt="imageAlt">
    </div>
    <div v-show="loading">
      <i class="fas fa-spinner fa-spin fa-10x"></i>
    </div>
  </div>
</template>

<script>
export default {
  name: "ScryfallCardImage",
  props: [
      'imageSrc',
      'imageAlt',
      'loading'
  ]
}
</script>

<style module>
  .scryfall-image {
    max-width: 336px;
  }
</style>
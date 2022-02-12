<template>
  <div class="text-center">
    <img class="w-100 scryfall-image" src="{{ imageSrc }}" alt="{{ imageAlt }}">
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

<style>
  .scryfall-image {
    max-width: 336px;
  }
</style>
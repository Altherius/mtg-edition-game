<template>
  <div class="text-center">
    Score : {{ score }}
  </div>
</template>

<script>
export default {
  name: "Score",
  props: [
      'score'
  ],
}
</script>
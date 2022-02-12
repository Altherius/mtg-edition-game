<template>
  <div :class="{ 'alert': true, 'alert-success': correct, 'alert-danger': !correct }">
    <span v-if="actualAnswer === givenAnswer">Correct! It was {{ actualAnswer }} indeed!</span>
    <span v-if="actualAnswer !== givenAnswer">Incorrect. The correct answer was {{ actualAnswer }}</span>
  </div>
</template>

<script>
export default {
  name: "QuizAnswerInfo",
  props: [
      'givenAnswer',
      'actualAnswer'
  ],
  computed: {
    correct() {
      return this.givenAnswer === this.actualAnswer
    }
  }
}
</script>
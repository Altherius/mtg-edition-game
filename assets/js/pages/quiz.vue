<template>
  <div class="card">
    <div class="card-body">
      <h1 class="text-center">
        Guess the edition
      </h1>
      <hr>
      <scryfall-card-component :image-src="currentImageSrc" :image-alt="currentImageAlt" :loading="imageLoading" :set="imageSetId"/>
      <hr>
      <div class="text-center">
        <div class="row">
          <div class="col-12 col-md-6 col-lg-4 mx-auto">
            <div class="text-center">

              <div class="form-group my-2">
                <multi-select v-model="selected" :options="sets" label="name" placeholder="Pick a set" />
              </div>

              <quiz-answer-info-component v-if="givenAnswer" :given-answer="givenAnswer" :actual-answer="actualAnswer" />

              <div class="form-group my-2">
                <button type="button" class="btn btn-primary" @click="scoreCheck" :disabled="imageLoading">Guess</button>
              </div>

            </div>
          </div>
        </div>
      </div>
      <score-component :score="score" />
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import ScryfallCardComponent from '@/components/scryfall_card_image';
import ScoreComponent from '@/components/score';
import QuizAnswerInfoComponent from '@/components/quiz_answer_info';
import MultiSelect from 'vue-multiselect';

export default {
  name: 'Quiz',
  components: {
    ScryfallCardComponent,
    ScoreComponent,
    QuizAnswerInfoComponent,
    MultiSelect
  },
  data() {
    return {
      selected: null,
      currentImageSrc: "#",
      currentImageAlt: "",
      currentSet: "",
      imageLoading: true,
      imageSetId: '',
      score: 0,
      sets: [],
      givenAnswer: null,
      actualAnswer: null,
    }
  },
  methods: {
    scoreCheck() {

      this.actualAnswer = this.currentSet;

      axios.get('/api/sets/' + this.selected.id).then((response) => {
        let json = response.data;
        let givenSet = json['scryfallUuid'];
        this.givenAnswer = json['name'];

        if (givenSet === this.imageSetId) {
          this.score++;
        } else {
          if (this.score >= 5) {
            axios.post('/api/high_scores', {'score': this.score});
          }
          this.score = 0;
        }
      });

      this.imageLoading = true;
      this.currentImageSrc = "#";
      this.currentImageAlt = "";

      axios.get('https://api.scryfall.com/cards/random?q=not:digital not:promo&unique=prints').then((response) => {
        let json = response.data;
        this.currentImageSrc = json.image_uris.normal;
        this.currentImageAlt = json.name;
        this.imageSetId = json.set_id;
        this.currentSet = json.set_name;
        this.imageLoading = false;
      });
    }
  },
  mounted() {
    axios.get('/api/sets').then((response) => {
      let json = response.data;
      this.sets = json['hydra:member'];
    });

    axios.get('https://api.scryfall.com/cards/random?q=not:digital not:promo&unique=prints').then((response) => {
      let json = response.data;
      this.currentImageSrc = json.image_uris.normal;
      this.currentImageAlt = json.name;
      this.imageSetId = json.set_id;
      this.currentSet = json.set_name;
      this.imageLoading = false;
    });
  }
}
</script>
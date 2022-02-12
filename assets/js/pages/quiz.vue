<template>
  <div class="card">
    <div class="card-body">
      <h1 class="text-center">
        Guess the edition
      </h1>
      <hr>
      <scryfall-card-component :image-src="currentImageSrc" :image-alt="currentImageAlt" :loading="imageLoading"/>
      <hr>
      <div class="text-center">
        <div class="row">
          <div class="col-auto mx-auto">
            <div class="text-center">

              <div class="form-group my-2">
                <select name="guess" id="guess" class="form-select">
                  <option v-for="set in sets" :value="set.id" :key="set.id">{{ set.name }}</option>
                </select>
              </div>

              <div class="form-group my-2">
                <button type="button" class="btn btn-primary" @click="scoreCheck">Guess</button>
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

export default {
  name: 'Quiz',
  components: {
    ScryfallCardComponent,
    ScoreComponent
  },
  data() {
    return {
      currentImageSrc: "#",
      currentImageAlt: "",
      imageLoading: true,
      score: 0,
      sets: []
    }
  },
  methods: {
    scoreCheck() {
      this.score++;
      this.imageLoading = true;
      this.currentImageSrc = "#";
      this.currentImageAlt = "";

      axios.get('https://api.scryfall.com/cards/random').then((response) => {
        let json = response.data;
        this.currentImageSrc = json.image_uris.normal;
        this.currentImageAlt = json.name;
        this.imageLoading = false;
      });
    }
  },
  mounted() {
    axios.get('https://api.scryfall.com/cards/random').then((response) => {
      let json = response.data;
      this.currentImageSrc = json.image_uris.normal;
      this.currentImageAlt = json.name;
      this.imageLoading = false;
    });
  }
}
</script>
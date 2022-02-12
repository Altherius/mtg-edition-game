<template>
  <div class="card">
    <div class="card-body">
      <h1 class="text-center">
        Guess the edition
      </h1>
      <hr>
      <scryfall-card-component image-src="https://via.placeholder.com/672x936" image-alt="A random card"/>
      <hr>
      <div class="text-center">
        <div class="row">
          <div class="col-auto mx-auto">
            <div class="text-center">

              <div class="form-group my-2">
                <select name="guess" id="guess" class="form-select">
                  <option value="">Limited Edition Alpha</option>
                </select>
              </div>

              <div class="form-group my-2">
                <button type="button" class="btn btn-primary">Guess</button>
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
      score: 0
    }
  }
}
</script>
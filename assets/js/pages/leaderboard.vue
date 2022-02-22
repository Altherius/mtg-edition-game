<template>
  <div class="card">
    <div class="card-body">
      <h1 class="text-center">Leaderboard</h1>
      <hr>

      <table class="table">
        <thead>
          <tr>
            <th>User</th>
            <th>Score</th>
            <th>Scored at</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="score in scores" :key="score.id">
            <td>{{ score.user.firstname }} {{ score.user.lastname }}</td>
            <td>{{ score.score }}</td>
            <td>{{ format_date(score.scoredAt) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

</template>

<script>
import axios from 'axios';
import moment from 'moment';

export default {
  name: 'Quiz',
  data() {
    return {
      scores: [],
      loading: true
    }
  },
  mounted() {
    axios.get('/api/high_scores/distinct').then((response) => {
      let json = response.data;
      this.scores = json['hydra:member'];
      this.loading = false;
    });
  },
  methods: {
    format_date(value) {
      if(value) {
        return moment(String(value)).format('DD/MM/YYYY HH:mm') + ' (' + moment(String(value)).fromNow() + ')';
      }
    }
  }
}
</script>
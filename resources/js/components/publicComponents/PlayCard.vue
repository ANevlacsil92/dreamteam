<template>
<a class="anchor-no-dec" :href="'/bisher-gespielt/' + play.shortlink_url">
  <div class="play-card">
    <img :src="'/images/plays/' + play.shortlink_url + '/' + play.cover_photo_url" class="play-card-image"/>
    <h1 class="play-card-year" v-if="play.production_rep_year">{{ play.production_year }}{{ play.production_year != play.production_rep_year ? " - " + play.production_rep_year : "" }}</h1>
    <h1 class="play-card-year" v-else>{{ play.production_year }}</h1>
  </div>
</a>
</template>

<script>
export default {
  props: ["play"],
  data() {
    return {
      plays:[],
    };
  },
  methods: {
    loadData: function() {
      let _this = this;

      $.ajax({
        url: "/api/plays",
        success: function (data) {
          // revert order

          _this.plays = data.reverse();
        },
      });
    },
  },
  mounted() {
    this.loadData();
  },
};
</script>

<style scoped>

</style>
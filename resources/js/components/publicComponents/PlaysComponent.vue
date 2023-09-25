<template>
  <div class="container container-main">
    <div class="row mt-5 mb-5">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Bisher Gespielt
        </h1>
      </div>
    </div>      
    <div class="row mb-5 d-flex justify-content-center">
      <div class="mt-4 col-sm-12 col-md-6 col-lg-4 d-flex justify-content-center" v-for="(play) in plays" v-bind:key="play.id">
        <play-card :play="play"></play-card>
        
      </div>     
    </div>     
  </div>
</template>

<script>
import PlayCard from './PlayCard.vue';
export default {
  components: { PlayCard },
  props: [],
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
h1, h3 {
color: #f39200!important;
}
</style>
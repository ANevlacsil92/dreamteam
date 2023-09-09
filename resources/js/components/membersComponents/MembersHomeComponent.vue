<template>
  <div class="container container-main">
    <div class="row mt-5">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Willkommen im Memberbereich</h1>
      </div>
    </div>
    <div class="row mt-5">
      <div class="col p-0 d-flex">
        <h2>Infos zu den Stücken</h2>
      </div>
    </div>
    <div class="row mb-3" v-for="play in plays" v-bind:key="play.id">
      <a :href='"/members/play-downloads/" + play.id'>{{ play.name }}</a>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      plays: [],
    };
  },
  methods: {
    retrievePlays: function () {
      let _this = this;

      $.ajax({
        url: "/api/plays",
        success: function (data) {
          _this.plays = data;
        },
      });
    },
  },
  mounted() {
    console.log("mounted");
    this.retrievePlays();
  },
};
</script>

<style scoped>
h1,
h3 {
  color: #f39200 !important;
}
</style>
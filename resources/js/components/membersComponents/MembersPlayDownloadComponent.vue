<template>
  <div class="container container-main">
    <div class="row mt-5"  v-if="play">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>{{ play.name }}</h1>
      </div>
    </div>
    <div class="row mt-5">
      <div class="col p-0 d-flex">
        <a :href="'/members/textbook/' + params.playId">
          <h2>Zum Textbuch</h2>
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user", "params"],
  data() {
    return {
      play: null
    };
  },
  methods: {
    retrievePlayTitle: function () {
      let _this = this;

      $.ajax({
        url: "/api/plays",
        data: {
          playId: _this.params.playId,
        },
        success: function (data) {
          _this.play = data;
        },
      });
    },
  },
  mounted() {
    console.log("mounted2");
    this.retrievePlayTitle();
  },
};
</script>

<style scoped>
h1,
h2 {
  color: #f39200 !important;
}
</style>
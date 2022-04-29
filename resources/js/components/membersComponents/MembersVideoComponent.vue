<template>
  <div class="container container-main">
    <div class="row mt-5" v-if="play">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>{{ play.name }} - Videos</h1>
      </div>
    </div>
    <div class="row mt-5">
      <video width="320" height="240" controls>
        <source src="" type="video/mp4" />
        Your browser does not support the video tag.
      </video>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user", "params"],
  data() {
    return {
      play: null,
      scenes: [],
      roles: [],
      selectedRole: null,
      activeLine: null,
    };
  },
  methods: {
    saveLine: function () {
      let _this = this;

      $.ajax({
        url: "/api/textbook/change-line",
        method: "POST",
        headers: {
          Authorization: "Bearer 1|YCMsFRtzv9xDEzZ92UsaaZCBMeLtSyOoDPfdH1sO",
        },
        data: {
          line: _this.activeLine,
        },
        success: function (data) {
          $("#siteNoteModal").modal("hide");
        },
      });
    },
    openmodal: function (line) {
      console.log(document.getElementById("btn-modal"));
      this.activeLine = line;
      $("#siteNoteModal").modal("show");
    },
    pad: function (num, size) {
      num = num.toString();
      while (num.length < size) num = "0" + num;
      return num;
    },
    retrieveTextBook: function () {
      let _this = this;

      $.ajax({
        url: "/api/play-textbook",
        headers: {
          Authorization: "Bearer 1|YCMsFRtzv9xDEzZ92UsaaZCBMeLtSyOoDPfdH1sO",
        },
        data: {
          playId: 1,
        },
        success: function (data) {
          _this.scenes = data;
        },
      });
    },
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
    this.retrievePlayTitle();
    this.retrieveTextBook();
  },
};
</script>

<style scoped>
h1,
h2,
h3 {
  color: #f39200 !important;
}

p {
  font-size: large;
}

.line-number {
  font-size: smaller;
}

.selected-text {
  background-color: #fff01f;
}

.modal-dialog {
  max-width: 1000px;
}
</style>
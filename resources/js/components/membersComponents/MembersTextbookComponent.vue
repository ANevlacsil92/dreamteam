<template>
  <div class="container container-main">
    <div class="row mt-5" v-if="play">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>{{ play.name }}</h1>
      </div>
    </div>
    <div class="row mt-2" v-if="play">
      <div class="col-2">Markiere Text für:</div>
      <div class="col-10">
        <select v-model="selectedRole">
          <option value=""></option>
          <option v-for="role in play.roles" v-bind:key="role.id">
            {{ role.name }}
          </option>
        </select>
      </div>
    </div>
    <div class="row mt-5" v-for="scene in scenes" v-bind:key="scene.id">
      <div class="container">
        <div class="row">
          <h2>{{ scene.title }}</h2>
        </div>

        <div class="row">
          <h3>{{ scene.subtitle }}</h3>
        </div>

        <div class="row mt-3 mb-2 d-flex justify-content-center">
          <p>{{ scene.description }}</p>
        </div>

        <div
          class="row"
          v-for="line in scene.play_textbook"
          v-bind:key="line.id"
        >
          <div class="container m-0 p-0">
            <div
              :class="
                selectedRole != '' && line.said_by.includes(selectedRole)
                  ? 'row selected-text'
                  : 'row'
              "
            >
              <div class="col-md-3">
                <b
                  ><span class="line-number">{{
                    pad(line.linenumber, 4)
                  }}</span>
                  <span class="said-by">{{ line.said_by }}:</span></b
                >
              </div>
              <div class="col-md-9">
                <span class="said-by">{{ line.text }}</span>
              </div>
            </div>
            <div
              class="row d-flex justify-content-center mt-3 mb-3 p-0"
              v-if="line.following_stage_direction"
            >
              <b>{{ line.following_stage_direction }}</b>
            </div>
          </div>
        </div>
      </div>
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
    };
  },
  methods: {
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
</style>
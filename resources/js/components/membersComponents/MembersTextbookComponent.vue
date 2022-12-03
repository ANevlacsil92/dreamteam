<template>
  <div class="container container-main">
    <div class="row nav-row" id="btn-modal">
      <!-- Button trigger modal -->
      <button
        type="button"
        class="btn btn-link"
        data-bs-toggle="modal"
        data-bs-target="#siteNoteModal"
        hidden
      >
        Impressum
      </button>
    </div>
    <div class="row">
      <!-- Modal -->
      <div
        class="modal fade"
        id="siteNoteModal"
        tabindex="-1"
        aria-labelledby="siteNoteModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <div class="container">
                <div class="row col-12 mb-3">
                  <div class="col-md-2">
                    <p>Zeile:</p>
                  </div>
                  <div class="col-md-10">
                    <input
                      type="text"
                      v-model="activeLine.linenumber"
                      v-if="activeLine"
                      style="width: 100%"
                      disabled
                    />
                  </div>
                </div>
                <div class="row col-12 mb-3">
                  <div class="col-md-2">
                    <p>Text:</p>
                  </div>
                  <div class="col-md-10">
                    <textarea
                      id="textarea"
                      class="form-control"
                      name="textarea"
                      rows="5"
                      v-if="activeLine"
                      v-model="activeLine.text"
                    />
                  </div>
                </div>
                <div class="row col-12 mb-3">
                  <div class="col-md-2">
                    <p>Nachfolgende<br/>Regieanweisung:</p>
                  </div>
                  <div class="col-md-10">
                    <textarea
                      id="textarea"
                      class="form-control"
                      name="textarea"
                      rows="5"
                      v-if="activeLine"
                      v-model="activeLine.following_stage_direction"
                    />
                  </div>
                </div>
                <div class="row col-1">
                  <button
                    type="button"
                    class="btn btn-light"
                    v-on:click="saveLine()"
                  >
                    Speichern
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-5" v-if="play">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>{{ play.name }} - Textbuch</h1>
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
    <div
      class="row mt-5 pr-2 pl-2"
      v-for="scene in scenes"
      v-bind:key="scene.id"
    >
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
          v-on:dblclick="openmodal(line)"
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
        },
        data: {
          playId: _this.params.playId,
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
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
                <div class="row col-12">
                  <div class="col-auto">
                    <button
                    type="button"
                    class="btn btn-light"
                    v-on:click="saveLine()"
                    >
                    Speichern
                  </button>
                  </div>
                  <div class="col-auto">
                    <button
                    type="button"
                    class="btn btn-light"
                    v-on:click="toggleLineDelete()"
                    v-if="activeLine" 
                    >
                      {{ activeLine.deleted_at ? 'Wiederherstellen' : 'Löschen' }}
                    </button>
                  </div>
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
    <div class="row d-flex align-items-center mt-2 mb-2" v-if="play">
      <div class="col-2">Markiere Text für:</div>
      <div class="col-5">
        <select v-model="selectedRole">
          <option value=""></option>
          <option v-for="role in play.roles" v-bind:key="role.id">
            {{ role.name }}
          </option>
        </select>
      </div>
      <div class="col-md-3 d-flex align-items-center">
        <input
          type="checkbox"
          v-model="showDeleted"
          id="showDeleted"
          name="showDeleted"
          class="mr-1"/>
        <label for="showDeleted" class="m-0">Gelöschte Texte anzeigen</label>
      </div>
      <div class="col-md-2">
        <button class="btn btn-nostyle" @click="changeFont('bigger')" style="font-size: 1.4rem;"><span>Aa+</span></button>
        <button class="btn btn-nostyle" @click="changeFont('smaller')" style="font-size: 1.1rem;"><span>Aa-</span></button>
      </div>
    </div>
    <div
      class="row mt-3 pr-2 pl-2"
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

        <div :class="'tb-' + fs">

          
          <div class="row mt-3 mb-2 d-flex justify-content-center">
            <p class="description">{{ scene.description }}</p>
          </div>
            
          <div
            class="row"
            v-for="line in scene.play_textbook"
            v-bind:key="line.id"
            v-on:dblclick="openmodal(line)"
          >
            <div class="container m-0 p-0" v-if="!line.deleted_at || showDeleted" :class="{'crossed': showDeleted && line.deleted_at}">
              <div
                :class="
                  selectedRole != '' && line.said_by.includes(selectedRole)
                    ? 'row no-print-break selected-text'
                    : 'row no-print-break'
                "
              >
                <div class="col-sm-3">
                  <b
                    ><span class="line-number">{{
                      pad(line.linenumber, 4)
                    }}</span>
                    <span class="said-by">{{ line.said_by }}:</span></b
                  >
                </div>
                <div class="col-sm-9">
                  <span class="said-by" v-html="line.formattedText"></span>
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
  </div>
</template>

<script>
export default {
  props: ["user", "params"],
  data() {
    return {
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      play: null,
      scenes: [],
      roles: [],
      selectedRole: null,
      activeLine: null,
      showDeleted: false,
      fs: "small"
    };
  },
  methods: {
    changeFont: function(val) {
      
      if(val == "bigger"){
        if(this.fs == "x-small"){
          this.fs = "small";
        } else if(this.fs == "small"){
          this.fs = "medium";
        } else if(this.fs == "medium"){
          this.fs = "large";
        } else if(this.fs == "large"){
          this.fs = "x-large";
        } else if(this.fs == "x-large"){
          this.fs = "xx-large";
        }
      } else {
        if(this.fs == "xx-large"){
          this.fs = "x-large";
        } else if(this.fs == "x-large"){
          this.fs = "large";
        } else if(this.fs == "large"){
          this.fs = "medium";
        } else if(this.fs == "medium"){
          this.fs = "small";
        } else if(this.fs == "small"){
          this.fs = "x-small";
        }
      }

      localStorage.setItem("fontsize", this.fs);

    },
    saveLine: function () {
      let _this = this;

      $.ajax({
        url: "/api/textbook/change-line",
        method: "POST",
        headers: {
          'X-CSRF-TOKEN': _this.csrf
        },
        data: {
          line: _this.activeLine,
        },
        success: function (data) {
          $("#siteNoteModal").modal("hide");
        },
      });
    },
    toggleLineDelete: function () {
      let _this = this;

      $.ajax({
        url: "/api/textbook/toggle-line-delete",
        method: "POST",
        headers: {
          'X-CSRF-TOKEN': _this.csrf
        },
        data: {
          line: _this.activeLine,
        },
        success: function (data) {
          _this.activeLine.deleted_at = data.deleted_at;
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

    var fs = localStorage.getItem("fontsize");
    console.log(fs)

    if(fs){
      this.fs = fs;
    } else {
      this.fs = "small";
    }
  },
};
</script>

<style scoped lang="scss">
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
  // span elements in the selected-text class which is not of class no-bg
  span { 
    background-color: #fff01f;

    ::v-deep .no-bg {
      background-color: #FCE8CB;
    }

    @media print {
      ::v-deep .no-bg {
        background-color: #fff;
      }
    }
  }
}

.modal-dialog {
  max-width: 1000px;
}

.btn-nostyle {
  background: none;
  border: none;
}

.tb-x-small {
  .description{
    font-size: 0.7rem;
  }

  .said-by {
    font-size: 0.7rem;
  }
}

.tb-small {
  .description{
    font-size: 0.9rem;
  }

  .said-by {
    font-size: 0.9rem;
  }
}

.tb-medium {
  .description{
    font-size: 1.1rem;
  }
  
  .said-by {
    font-size: 1.1rem;
  }
}

.tb-large {
  .description{
    font-size: 1.3rem;
  }
  
  .said-by {
    font-size: 1.3rem;
  }
}

.tb-x-large {
  .description{
    font-size: 1.5rem;
  }
  
  .said-by {
    font-size: 1.5rem;
  }
}

.tb-xx-large {
  .description{
    font-size: 1.7rem;
  }
  
  .said-by {
    font-size: 1.7rem;
  }
}

.crossed {
  span {
    text-decoration: line-through;
  }
} 

@media print {
  .no-print-break {
    page-break-inside: avoid;
  } 
}
</style>
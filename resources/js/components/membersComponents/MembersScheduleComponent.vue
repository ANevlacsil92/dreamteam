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
                    <p>Datum:</p>
                  </div>
                  <div class="col-md-10">
                    <input
                      type="date"
                      v-model="activeAppointment.practice_date"
                      v-if="activeAppointment"
                      style="width: 100%"
                    />
                  </div>
                </div>
                <div class="row col-12 mb-3">
                  <div class="col-md-2">
                    <p>Start:</p>
                  </div>
                  <div class="col-md-10">
                    <input
                      id="text"
                      class="form-control"
                      name="textarea"
                      rows="5"
                      v-if="activeAppointment"
                      v-model="activeAppointment.start_line"
                      @change="calculateParticipants"
                    />
                  </div>
                </div>
                <div class="row col-12 mb-3">
                  <div class="col-md-2">
                    <p>Ende:</p>
                  </div>
                  <div class="col-md-10">
                    <input
                      id="text"
                      class="form-control"
                      name="textarea"
                      rows="5"
                      v-if="activeAppointment"
                      v-model="activeAppointment.end_line"
                      @change="calculateParticipants"
                    />
                  </div>
                </div>
                <div class="row col-12 mb-3">
                  <div class="col-md-2">
                    <p>Kommentar:</p>
                  </div>
                  <div class="col-md-10">
                    <input
                      id="text"
                      class="form-control"
                      name="textarea"
                      rows="5"
                      v-if="activeAppointment"
                      v-model="activeAppointment.comment"
                    />
                  </div>
                </div>
                <div class="row col-12 mb-3">
                  <div class="col-md-2">
                    <p>Darsteller:innen:</p>
                  </div>
                  <div class="col-md-10" v-if="activeAppointment">
                    <input
                      disabled
                      id="text"
                      class="form-control"
                      name="textarea"
                      rows="5"
                      v-if="textlines"
                      v-model="displayParticipants"
                    />
                  </div>
                </div>
                <div class="row col-1">
                  <button
                    type="button"
                    class="btn btn-light"
                    v-on:click="saveAppointment()"
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
        <h1>{{ play.name }} - Probenplan</h1>
      </div>
    </div>
    <div class="row mt-5 mb-4">
      <button
        type="button"
        class="btn btn-light"
        data-bs-toggle="modal"
        data-bs-target="#siteNoteModal"
        v-on:click="addNewAppointment()"
      >
        Neuen Termin Hinzufügen
      </button>
    </div>
    <div class="row mt-2">
      <div class="container">
        <div class="row mb-3">
          <div class="col-md-2"><b>Datum</b></div>
          <div class="col-md-2"><b>Zeilen</b></div>
          <div class="col-md-5"><b>Rollen</b></div>
          <div class="col-md-2"><b>Anmerkung</b></div>
          <div class="col-md-1"><b>Löschen</b></div>
        </div>
        <div
          class="row mb-2"
          v-for="rehearsal in schedule"
          v-bind:key="rehearsal.id"
          v-on:dblclick="openmodal(rehearsal)"
        >
          <div class="col-md-2">
            {{
              moment(rehearsal.practice_date, "YYYY-MM-DD").format("DD.MM.YYYY")
            }}
          </div>
          <div class="col-md-2">
            {{
              rehearsal.start_line && rehearsal.end_line
                ? rehearsal.start_line + "-" + rehearsal.end_line
                : "tbd"
            }}
          </div>
          <div class="col-md-5">
            {{ rehearsal.participants ? rehearsal.participants : "tbd" }}
          </div>
          <div class="col-md-2">{{ rehearsal.comment }}</div>
          <div class="col-md-1 d-flex justify-content-center">
            <button
              type="button"
              class="btn btn-danger"
              v-on:click="deleteAppointment(rehearsal.id)"
            >
              X
            </button>
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
      schedule: [],
      textlines: [],
      displayParticipants: "",
      activeAppointment: null,
      activeAppointmentIsNew: false,
    };
  },
  methods: {
    deleteAppointment: function (id) {
      if (confirm("Soll der Probentermin gelöscht werden?")) {
        let _this = this;

        $.ajax({
          url: "/api/schedule/change-appointment",
          method: "DELETE",
          headers: {
            'X-CSRF-TOKEN': _this.csrf
          },
          data: {
            id: id,
            play_id: _this.play.id,
          },
          success: function (data) {
            _this.schedule = data;
            console.log(data);
            $("#siteNoteModal").modal("hide");
          },
        });
      }
    },
    addNewAppointment: function () {
      this.activeAppointment = {
        play_id: this.play.id,
        practice_date: null,
        start_line: null,
        end_line: null,
        comment: null,
      };
      this.displayParticipants = "";
      this.activeAppointmentIsNew = true;
    },
    saveAppointment: function () {
      let _this = this;

      $.ajax({
        url: "/api/schedule/change-appointment",
        method: "POST",
        headers: {
          'X-CSRF-TOKEN': _this.csrf
        },
        data: {
          appointment: _this.activeAppointment,
          isNew: _this.activeAppointmentIsNew,
        },
        success: function (data) {
          _this.schedule = data;
          $("#siteNoteModal").modal("hide");
        },
      });
    },
    openmodal: function (appointment) {
      console.log(document.getElementById("btn-modal"));
      this.activeAppointmentIsNew = false;
      this.activeAppointment = appointment;
      this.calculateParticipants();
      
      $("#siteNoteModal").modal("show");
    },
    calculateParticipants: function(){

      if(!this.activeAppointment.start_line || !this.activeAppointment.end_line){
        return;
      }

      let _this = this;

      console.log(this.textlines);
      
      var filteredLines = this.textlines.filter((line) => {
        return line.linenumber >= _this.activeAppointment.start_line && line.linenumber <= _this.activeAppointment.end_line;
      });

      console.log(filteredLines);

      var participants = filteredLines.map((line) => {
        return line.said_by;
      });

      // filter all participants with space in name
      participants = participants.filter((item) => {
        return item.indexOf(" ") === -1;
      });

      // sort names alphabetically
      participants.sort();

      this.displayParticipants = participants.filter((item, index) => {
        return participants.indexOf(item) === index;
      }).join(", ");
    },
    retrieveSchedule: function () {
      let _this = this;

      $.ajax({
        url: "/api/play-schedule",
        headers: {
        },
        data: {
          playId: _this.params.playId,
        },
        success: function (data) {
          _this.schedule = data.schedule;
          _this.textlines = data.textlines;
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
    this.retrieveSchedule();
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
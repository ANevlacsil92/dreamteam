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
        <div class="row">
          <div class="col-2"><b>Datum</b></div>
          <div class="col-2"><b>Zeilen</b></div>
          <div class="col-6"><b>Rollen</b></div>
          <div class="col-2"><b>Anmerkung</b></div>
        </div>
        <div class="row" v-for="rehearsal in schedule" v-bind:key="rehearsal.id" 
          v-on:dblclick="openmodal(rehearsal)">
          <div class="col-2">{{ moment(rehearsal.practice_date,"YYYY-MM-DD").format("DD.MM.YYYY") }}</div>
          <div class="col-2">{{ (rehearsal.start_line && rehearsal.end_line) ? 
            rehearsal.start_line + "-" + rehearsal.end_line : "tbd"}}
          </div>
          <div class="col-6">{{ (rehearsal.participants) ? rehearsal.participants : "tbd" }}</div>
          <div class="col-2">{{ rehearsal.comment }}</div>
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
      schedule: [],
      roles: [],
      activeAppointment: null,
      activeAppointmentIsNew: false,
    };
  },
  methods: {
    addNewAppointment: function(){
      this.activeAppointment = {
        play_id: this.play.id,
        practice_date: null,
        start_line: null,
        end_line: null,
        comment: null,
      };
      this.activeAppointmentIsNew = true;
    },
    saveAppointment: function () {
      let _this = this;

      $.ajax({
        url: "/api/schedule/change-appointment",
        method: "POST",
        headers: {
          Authorization: "Bearer 1|YCMsFRtzv9xDEzZ92UsaaZCBMeLtSyOoDPfdH1sO",
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
      $("#siteNoteModal").modal("show");
    },
    retrieveSchedule: function () {
      let _this = this;

      $.ajax({
        url: "/api/play-schedule",
        headers: {
          Authorization: "Bearer 1|YCMsFRtzv9xDEzZ92UsaaZCBMeLtSyOoDPfdH1sO",
        },
        data: {
          playId: _this.params.playId,
        },
        success: function (data) { 
          _this.schedule = data;
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
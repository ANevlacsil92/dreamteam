<template>
  <div class="container container-main">
    <div class="row mt-5">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Dein Profil - {{ userDisplay.name }}</h1>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-4">
        <img
          :src="userDisplay.extended_user_property.photo_url"
          v-if="userDisplay.extended_user_property.photo_url"
          class="w-100"
        />
        <img
          :src="'/storage/imgs/profilepictures/no-image-found.jpg'"
          v-else
          class="w-100"
        />
      </div>
      <div class="col-md-8">
        <textarea
          class="w-100 h-100"
          v-model="userDisplay.extended_user_property.about_me"
          placeholder="add multiple lines"
        ></textarea>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-4">
        <button class="btn btn-light w-100" v-on:click="changePicture()">
          Bild ändern
        </button>
        <form id="file-form">
          <input
            class="form-control"
            type="file"
            accept=".jpg, .jpeg, .png, .svg"
            ref="pictures"
            name="file"
            id="formFileMultiple"
            @change="addPhoto"
            hidden
          />
        </form>
      </div>
      <div class="col-md-8">
        <button class="btn btn-success w-100" v-on:click="saveSettings()">
          Änderungen Speichern
        </button>
      </div>
    </div><div class="row mt-3" v-if="showSuccessMsg">
      <div class="col-md-12">
        <span class="text-success">Daten wurden gespeichert!</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["user", "params"],
  data() {
    return {
      plays: [],
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
      userDisplay: null,
      showSuccessMsg: false,
    };
  },
  methods: {
    saveSettings() {
      let _this = this;
      this.showSuccessMsg = false;

      // get file form
      var form = document.getElementById("file-form");
      // create form data out of form
      var fd = new FormData(form);
      // append id to formdata
      fd.append("id", this.user.id);
      fd.append("description", this.userDisplay.extended_user_property.about_me);

      $.ajax({
        url: "/api/member-settings/save-data",
        type: "POST",
        headers: {
          "x-CSRF-TOKEN": _this.csrf,
        },
        data: fd,
        contentType: false,
        cache: false,
        processData: false,
        success() {
          _this.showSuccessMsg = true;
        },
      });
    },
    changePicture() {
      this.$refs.pictures.click();
    },
    addPhoto: function () {
      console.log(URL.createObjectURL(this.$refs.pictures.files[0]));
      this.userDisplay.extended_user_property.photo_url = URL.createObjectURL(
        this.$refs.pictures.files[0]
      );
    },
  },
  mounted() {
    this.userDisplay = this.params.user;
    this.userDisplay.extended_user_property.photo_url = '/storage/' + this.userDisplay.extended_user_property.photo_url;
    console.log("mounted");
  },
};
</script>

<style scoped>
h1,
h3 {
  color: #f39200 !important;
}
</style>
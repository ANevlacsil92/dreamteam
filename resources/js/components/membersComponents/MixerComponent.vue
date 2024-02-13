<template>
  <div class="container container-main">
    <div class="row mt-5" v-if="play">
      <h1>Mixer - {{ play.name }}</h1>
    </div>

    <div class="row mt-5" v-if="play">
      
      <div class="col-4">
        <div class="row">
          <div class="col">
            <h2>Spotify Playlist</h2>
          </div>
        </div>
        <div class="row">
          <img class="mixer-img-pause" src="/storage/sounds/pause.png" v-on:click="pauseTrack"/>
        </div>
        <div class="row">
          <div class="track-grid" v-for="track in tracks" v-on:click="playTrack(track)">
            <img class="track-image" :src="track.image" />
            <p class="track-title">{{ track.name }}</p>
            <p class="track-artist">{{ track.artist }}</p>
          </div>
        </div>
      </div>
      
      <div class="col-4">
        <div class="row">
          <div class="col">
            <h2>Local Sounds</h2>
          </div>
        </div>
        <div class="row">
          <audio id="audio-player" controls>
          </audio>
        </div>
        <div class="row">
          <div class="sound-grid" v-for="sound in sounds" v-on:click="playSound(sound)">
            <img class="sound-image" v-if="sound.icon_filename" :src="'/storage/sounds/' + sound.icon_filename" />
            <img class="sound-image" v-else :src="'/storage/sounds/sound.jpg'"/>
            <p class="sound-title">{{ sound.name }}</p>
          </div>
        </div>
      </div>
      
      
      
      
      <div class="col-4">
        <div class="row">
          <div class="col">
            <h2>Light Control</h2>
          </div>
        </div>
        <div class="row">
          <div class="light-grid" v-for="light in lights" v-on:click="switchLight(light)">
            <p class="light-title">{{ light.name }}</p>
          </div>
        </div>
      </div>
      
      
      <div class="col-4">
        
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
      tracks: [],
      play: null,
      sounds: null,
      lights: null,
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
          _this.retrieveTracks();
          _this.retrieveSounds();
          _this.retrieveLights();
        },
      });
    },
    retrieveSounds: function () {
      let _this = this;

      $.ajax({
        url: "/mixer/get-sounds",
        success: function (data) {
          _this.sounds = data;
        },
      });
    },
    playSound: function (sound) {
      let audio = document.getElementById("audio-player");
      audio.src = "/storage/sounds/" + sound.filename;
      audio.play();
    },
    retrieveLights: function () {
      let _this = this;

      $.ajax({
        url: "/mixer/get-lights",
        success: function (data) {
          _this.lights = data;
        },
      });
    },
    switchLight: function (light) {
      let _this = this;

      $.ajax({
        url: "http://" + light.ip + light.endpoint,
        type: "PUT",
        data: light.body,
        success: function (data) {
          console.log(data);
        },
      });
    },
    renewToken: function (callback) {
      let _this = this;
      $.ajax({
        url: "/mixer/get-oauth2-authentication",
        success: function (data) {
          callback;
        },
      });
    },
    retrieveTracks: function (retry = false) {
      let _this = this;

      $.ajax({
        url: "/mixer/get-playlist-tracks",
        data: {
          playlist_id: _this.play.spotify_playlist_id,
        },
        success: function (data) {
          _this.tracks = data;
        },
        401: function(){
          console.log("401");
          _this.renewToken(_this.retrieveTracks(true))
        }
      });
    },
    playTrack: function (track, retry = false) {
      $.ajax({
        url: "/mixer/play-track",
        data: {
          context_uri: track.context_uri,
          position: track.position,
        },
        401: function (data) {
          _this.renewToken(_this.playTrack(track, true))
        },
      });
    },
    pauseTrack: function (track, retry = false) {
      $.ajax({
        url: "/mixer/pause-track",
        success: function (data) {
        },
        401: function(){
          _this.renewToken(_this.pauseTrack(track, true))
        }
      });
    },
  },
  mounted() {
    this.renewToken();
    this.retrievePlayTitle();
  },
};
</script>

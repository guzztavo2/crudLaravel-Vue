<template>
<section class="footer flexRow">
<p>Copyright 2023</p>
</section>
</template>
  
  <script lang="ts">
  import { Options, Vue } from 'vue-class-component';
  
  @Options({
    props: {
  
    },
    methods:{
    
    }
  })
  
  
  export default class footerApp extends Vue {

  } 
  
  
  </script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
  <style scoped>
  section.footer{
    height: 5%;
    background-color: var(--vermelho_1);
    align-items: center;
    justify-content: center;
    font-weight: 900;
    text-transform: uppercase;
    color:var(--branco);
    font-size: 1vw;
  }
  </style>
  
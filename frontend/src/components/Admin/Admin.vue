<template>
  <div class="admin-container">
    <div class="login-wrapper">
      <div class="image">
        <span class="circle"></span>
        <span class="star">
          <span v-for="(o, i) in randomStarNum" :key="i"></span>
        </span>
        <span class="fly-star"></span>
        <p class="title">
          HCBLOG
        </p>
      </div>
      <div class="content">
        <div class="title">
          <span>LOG IN</span>
        </div>
        <div class="input-block">
          <div class="row">
            <el-input type="text" placeholder="username" v-model="username" />
          </div>
          <div class="row">
            <el-input type="password" placeholder="password" v-model="password" @keyup.enter.native="login" />
          </div>
          <div class="row">
            <button @click="login">LOG IN</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/ecmascript-6">
export default {
  name: 'admin',
  data() {
    return {
      username: '',
      password: ''
    };
  },
  created() {
    this.authenticate();
  },
  computed: {
    randomStarNum() {
      return Math.ceil(Math.random() * 7 + 7);
    }
  },
  methods: {
    authenticate() {
      this.$axios.get('/api/authenticate')
        .then((response) => {
          const res = response.data;
          if (res.state === 200) {
            this.$router.push({ path: '/admin/editArticle' });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    login(e) {
      this.$axios.post('/api/login', {
        username: this.username,
        password: this.password
      })
        .then((response) => {
          const res = response.data;
          this.$message({
            message: res.message,
            type: res.state === 200 ? 'success' : 'error'
          });
          if (res.state === 200) {
            this.$router.push({ name: 'editArticle' });
          }
        })
        .catch((error) => {
          console.log(error);
        });
    }
  }
};
</script>

<style lang="scss">
.admin-container {
  background: linear-gradient(45deg, #211136, #bf5853);
  height: 100%;
  .login-wrapper {
    position: fixed;
    top: 50%;
    right: 50%;
    margin-top: -250px;
    margin-right: -490px;
    width: 900px;
    min-height: 500px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 120px rgba(0, 0, 0, 0.25);
    .image {
      position: relative;
      float: left;
      width: 50%;
      min-height: 500px;
      background: linear-gradient(45deg, #221334, #6c3049);
      .circle {
        position: absolute;
        top: 70px;
        left: 50%;
        margin-left: -100px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: linear-gradient(45deg, #df5555, #ef907a);
        overflow: hidden;
      }
      .circle::before, .circle::after {
        content: '';
        position: absolute;
        width: 150px;
        height: 40px;
        border-radius: 50px;
      }
      .circle::before {
        top: 30px;
        left: -38px;
        background: #c55c59;
      }
      .circle::after {
        bottom: 30px;
        right: -38px;
        background: #934555;
      }
      .star {
        position: absolute;
        display: block;
        span {
          position: absolute;
          border-radius: 50%;
          background: radial-gradient(#fff 10%,#fff 20%,rgba(72, 34, 64, 0));
          box-shadow: 0 0 7px #FFF;
        }
        @for $i from 1 through 14 {
          span:nth-child(#{$i}){
            $size: random(11) + 4px;
            top: random(400) + 50px;
            left: random(400) + 50px;
            width: $size;
            height: $size;
          }
        }
      }
      .fly-star {
        position: absolute;
        display: block;
      }
      .fly-star::before, .fly-star::after {
        content: '';
        position: absolute;
        width: 90px;
        height: 3px;
        transform: rotate(-45deg);
        background: linear-gradient(to right, rgba(255, 255, 255, 0.67), rgba(255,255,255,0));
      }
      .fly-star::before {
        top: 60px;
        left: 80px;
      }
      .fly-star::after {
        top: 210px;
        left: 332px;
        opacity: 0.6;
      }
      .title {
        margin-top: 365px;
        font-size: 25px;
        font-weight: 600;
        color: #af4b55;
        text-align: center;
        letter-spacing: 25px;
      }
    }
    .content {
      position: relative;
      float: right;
      width: 50%;
      min-height: 500px;
      color: #FFF;
      background: linear-gradient(45deg, #9a444e, #e06267);
      .title {
        margin: 40px auto;
        width: 120px;
        height: 30px;
        font-family: 'neo';
        font-size: 28px;
        font-weight: 600;
      }
      .input-block {
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -80px;
        margin-left: -150px;
        text-align: center;
        .row {
          input {
            padding: 0;
            margin-bottom: 20px;
            width: 300px;
            height: 30px;
            font-size: 18px;
            color: #FFF;
            background: none;
            border: none;
            border-bottom: 1px solid #FFF;
            border-radius: 0;
          }
          input:focus {
            outline: none;
          }
          input::-webkit-input-placeholder {
            color: #FFF;
          }
          input:-moz-placeholder {
            color: #FFF;
          }
          input::-moz-placeholder {
            color: #FFF;
          }
          input:-ms-input-placeholder {
            color: #FFF;
          }
          button {
            margin-top: 25px;
            width: 140px;
            height: 40px;
            font-size: 18px;
            color: #FFF;
            cursor: pointer;
            background: none;
            border: 1px solid #FFF;
            transition: 0.25s;
          }
          button:hover {
            background: rgba(255, 255, 255, 0.25);
          }
          button:focus {
            outline: none;
          }
        }
      }
    }
  }
}
@media screen and (max-width: 1000px) {
  .admin-container {
    .login-wrapper {
      width: 490px;
      margin-right: -245px;
      .image {
        display: none;
      }
      .content {
        width: 100%;
      }
    }
  }
}
</style>

import '@/App.scss';
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'app',
  setup() {
    return () => (
      <>
        <div id="nav">
          <router-link to="/">Home</router-link> | <router-link to="/about">About</router-link>
        </div>
        <router-view />
      </>
    );
  },
});

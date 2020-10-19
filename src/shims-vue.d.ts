declare module '*.vue' {
  import type { DefineComponent } from 'vue'
  const component: DefineComponent
  export default component
}


declare module '*.scss' {
  const scss: any;
  export default scss;
}

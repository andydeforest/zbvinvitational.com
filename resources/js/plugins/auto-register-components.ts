import { App } from 'vue';

function toPascalCase(str: string): string {
  return str
    .replace(/[^a-zA-Z0-9]/g, ' ') // remove symbols
    .replace(/(?:^|\s+)(\w)/g, (_, c) => c.toUpperCase()) // capitalize
    .replace(/\s+/g, ''); // remove spaces
}

export function registerGlobalComponents(app: App) {
  const components = import.meta.glob('../components/**/*.vue', {
    eager: true
  });

  Object.entries(components).forEach(([path, module]: any) => {
    // extract segments between "components/" and ".vue"
    const match = path.match(/components\/(.*)\.vue$/);
    if (!match) return;

    const segments = match[1].split('/');
    const name = toPascalCase(segments.join('-'));

    const component = module.default;

    if (!app._context.components[name]) {
      app.component(name, component);
    }
  });
}

const getters = {
  init_layout: state => state.app.init_layout,
  user: state => state.app.user,
  project: state => state.app.project,
  project_list: state => state.app.project_list,
  project_index: state => state.app.project_index,
  category_list: state => state.app.category_list,
  api_list: state => state.app.api_list,

  show_category: state => state.form.show_category,
  show_reg: state => state.form.show_reg,
  show_login: state => state.form.show_login,
  show_project: state => state.form.show_project
}
export default getters

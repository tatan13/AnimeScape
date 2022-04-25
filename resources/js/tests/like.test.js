import LikeCastComponent from "../components/LikeCastComponent";

const { mount } = require("@vue/test-utils");

test("Like cast true test", async() =>{
  const wrapper = mount(LikeCastComponent,{
    propsData:{
      propsCastId: 1,
      defaultIsLikeCast: true,
    }
  });
  expect(wrapper.text()).toBe('お気に入りを解除する');
})

test("Like cast false test", async() =>{
  const wrapper = mount(LikeCastComponent,{
    propsData:{
      propsCastId: 1,
      defaultIsLikeCast: false,
    }
  });
  expect(wrapper.text()).toBe('お気に入り声優として登録する');
})
import LikeCastComponent from "../components/LikeCastComponent";
import LikeCreaterComponent from "../components/LikeCreaterComponent";
import LikeUserComponent from "../components/LikeUserComponent";

const { mount } = require("@vue/test-utils");

test("Like cast true test", async () => {
  const wrapper = mount(LikeCastComponent, {
    propsData: {
      propsCastId: 1,
      defaultIsLikeCast: true,
    }
  });
  expect(wrapper.text()).toBe('お気に入りを解除する');
})

test("Like cast false test", async () => {
  const wrapper = mount(LikeCastComponent, {
    propsData: {
      propsCastId: 1,
      defaultIsLikeCast: false,
    }
  });
  expect(wrapper.text()).toBe('お気に入り声優として登録する');
})

test("Like creater true test", async () => {
  const wrapper = mount(LikeCreaterComponent, {
    propsData: {
      propsCreaterId: 1,
      defaultIsLikeCreater: true,
    }
  });
  expect(wrapper.text()).toBe('お気に入りを解除する');
})

test("Like creater false test", async () => {
  const wrapper = mount(LikeCreaterComponent, {
    propsData: {
      propsCreaterId: 1,
      defaultIsLikeCreater: false,
    }
  });
  expect(wrapper.text()).toBe('お気に入りクリエイターとして登録する');
})

test("Like user true test", async () => {
  const wrapper = mount(LikeUserComponent, {
    propsData: {
      propsId: 1,
      defaultLikedUserCount: 1,
      defaultIsLikeUser: true,
    }
  });
  expect(wrapper.text()).toBe('お気に入りユーザーを解除する');
})

test("Like user false test", async () => {
  const wrapper = mount(LikeUserComponent, {
    propsData: {
      propsId: 1,
      defaultLikedUserCount: 0,
      defaultIsLikeUser: false,
    }
  });
  expect(wrapper.text()).toBe('お気に入りユーザーとして登録する');
})
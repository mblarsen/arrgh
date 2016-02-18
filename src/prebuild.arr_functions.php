<?php
function arr($array = []) {
    return new \Arrgh\Arrgh($array);
}
function arr_collapse()
{
    return \Arrgh\Arrgh::collapse(...func_get_args());
}
function arr_contains()
{
    return \Arrgh\Arrgh::contains(...func_get_args());
}
function arr_except()
{
    return \Arrgh\Arrgh::except(...func_get_args());
}
function arr_map_assoc()
{
    return \Arrgh\Arrgh::map_assoc(...func_get_args());
}
function arr_only()
{
    return \Arrgh\Arrgh::only(...func_get_args());
}
function arr_sort_by()
{
    return \Arrgh\Arrgh::sort_by(...func_get_args());
}
function arr_depth()
{
    return \Arrgh\Arrgh::depth(...func_get_args());
}
function arr_even()
{
    return \Arrgh\Arrgh::even(...func_get_args());
}
function arr_first()
{
    return \Arrgh\Arrgh::first(...func_get_args());
}
function arr_get()
{
    return \Arrgh\Arrgh::get(...func_get_args());
}
function arr_head()
{
    return \Arrgh\Arrgh::head(...func_get_args());
}
function arr_is_collection()
{
    return \Arrgh\Arrgh::is_collection(...func_get_args());
}
function arr_last()
{
    return \Arrgh\Arrgh::last(...func_get_args());
}
function arr_odd()
{
    return \Arrgh\Arrgh::odd(...func_get_args());
}
function arr_partition()
{
    return \Arrgh\Arrgh::partition(...func_get_args());
}
function arr_tail()
{
    return \Arrgh\Arrgh::tail(...func_get_args());
}
function arr_change_key_case()
{
    return \Arrgh\Arrgh::change_key_case(...func_get_args());
}
function arr_chunk()
{
    return \Arrgh\Arrgh::chunk(...func_get_args());
}
function arr_column()
{
    return \Arrgh\Arrgh::column(...func_get_args());
}
function arr_combine()
{
    return \Arrgh\Arrgh::combine(...func_get_args());
}
function arr_count_values()
{
    return \Arrgh\Arrgh::count_values(...func_get_args());
}
function arr_diff()
{
    return \Arrgh\Arrgh::diff(...func_get_args());
}
function arr_diff_assoc()
{
    return \Arrgh\Arrgh::diff_assoc(...func_get_args());
}
function arr_diff_key()
{
    return \Arrgh\Arrgh::diff_key(...func_get_args());
}
function arr_diff_uassoc()
{
    return \Arrgh\Arrgh::diff_uassoc(...func_get_args());
}
function arr_diff_ukey()
{
    return \Arrgh\Arrgh::diff_ukey(...func_get_args());
}
function arr_fill()
{
    return \Arrgh\Arrgh::fill(...func_get_args());
}
function arr_fill_keys()
{
    return \Arrgh\Arrgh::fill_keys(...func_get_args());
}
function arr_filter()
{
    return \Arrgh\Arrgh::filter(...func_get_args());
}
function arr_flip()
{
    return \Arrgh\Arrgh::flip(...func_get_args());
}
function arr_intersect()
{
    return \Arrgh\Arrgh::intersect(...func_get_args());
}
function arr_intersect_assoc()
{
    return \Arrgh\Arrgh::intersect_assoc(...func_get_args());
}
function arr_intersect_key()
{
    return \Arrgh\Arrgh::intersect_key(...func_get_args());
}
function arr_intersect_uassoc()
{
    return \Arrgh\Arrgh::intersect_uassoc(...func_get_args());
}
function arr_intersect_ukey()
{
    return \Arrgh\Arrgh::intersect_ukey(...func_get_args());
}
function arr_keys()
{
    return \Arrgh\Arrgh::keys(...func_get_args());
}
function arr_merge()
{
    return \Arrgh\Arrgh::merge(...func_get_args());
}
function arr_merge_recursive()
{
    return \Arrgh\Arrgh::merge_recursive(...func_get_args());
}
function arr_pad()
{
    return \Arrgh\Arrgh::pad(...func_get_args());
}
function arr_product()
{
    return \Arrgh\Arrgh::product(...func_get_args());
}
function arr_rand()
{
    return \Arrgh\Arrgh::rand(...func_get_args());
}
function arr_reduce()
{
    return \Arrgh\Arrgh::reduce(...func_get_args());
}
function arr_replace()
{
    return \Arrgh\Arrgh::replace(...func_get_args());
}
function arr_replace_recursive()
{
    return \Arrgh\Arrgh::replace_recursive(...func_get_args());
}
function arr_reverse()
{
    return \Arrgh\Arrgh::reverse(...func_get_args());
}
function arr_slice()
{
    return \Arrgh\Arrgh::slice(...func_get_args());
}
function arr_sum()
{
    return \Arrgh\Arrgh::sum(...func_get_args());
}
function arr_udiff()
{
    return \Arrgh\Arrgh::udiff(...func_get_args());
}
function arr_udiff_assoc()
{
    return \Arrgh\Arrgh::udiff_assoc(...func_get_args());
}
function arr_udiff_uassoc()
{
    return \Arrgh\Arrgh::udiff_uassoc(...func_get_args());
}
function arr_uintersect()
{
    return \Arrgh\Arrgh::uintersect(...func_get_args());
}
function arr_uintersect_assoc()
{
    return \Arrgh\Arrgh::uintersect_assoc(...func_get_args());
}
function arr_uintersect_uassoc()
{
    return \Arrgh\Arrgh::uintersect_uassoc(...func_get_args());
}
function arr_unique()
{
    return \Arrgh\Arrgh::unique(...func_get_args());
}
function arr_values()
{
    return \Arrgh\Arrgh::values(...func_get_args());
}
function arr_count()
{
    return \Arrgh\Arrgh::count(...func_get_args());
}
function arr_max()
{
    return \Arrgh\Arrgh::max(...func_get_args());
}
function arr_min()
{
    return \Arrgh\Arrgh::min(...func_get_args());
}
function arr_range()
{
    return \Arrgh\Arrgh::range(...func_get_args());
}
function arr_sizeof()
{
    return \Arrgh\Arrgh::sizeof(...func_get_args());
}
function arr_map()
{
    return \Arrgh\Arrgh::map(...func_get_args());
}
function arr_key_exists()
{
    return \Arrgh\Arrgh::key_exists(...func_get_args());
}
function arr_search()
{
    return \Arrgh\Arrgh::search(...func_get_args());
}
function arr_implode()
{
    return \Arrgh\Arrgh::implode(...func_get_args());
}
function arr_in_array()
{
    return \Arrgh\Arrgh::in_array(...func_get_args());
}
function arr_join()
{
    return \Arrgh\Arrgh::join(...func_get_args());
}
function arr_push()
{
    return \Arrgh\Arrgh::push(...func_get_args());
}
function arr_splice()
{
    return \Arrgh\Arrgh::splice(...func_get_args());
}
function arr_unshift()
{
    return \Arrgh\Arrgh::unshift(...func_get_args());
}
function arr_walk()
{
    return \Arrgh\Arrgh::walk(...func_get_args());
}
function arr_walk_recursive()
{
    return \Arrgh\Arrgh::walk_recursive(...func_get_args());
}
function arr_arsort()
{
    return \Arrgh\Arrgh::arsort(...func_get_args());
}
function arr_asort()
{
    return \Arrgh\Arrgh::asort(...func_get_args());
}
function arr_krsort()
{
    return \Arrgh\Arrgh::krsort(...func_get_args());
}
function arr_ksort()
{
    return \Arrgh\Arrgh::ksort(...func_get_args());
}
function arr_natcasesort()
{
    return \Arrgh\Arrgh::natcasesort(...func_get_args());
}
function arr_natsort()
{
    return \Arrgh\Arrgh::natsort(...func_get_args());
}
function arr_rsort()
{
    return \Arrgh\Arrgh::rsort(...func_get_args());
}
function arr_shuffle()
{
    return \Arrgh\Arrgh::shuffle(...func_get_args());
}
function arr_sort()
{
    return \Arrgh\Arrgh::sort(...func_get_args());
}
function arr_uasort()
{
    return \Arrgh\Arrgh::uasort(...func_get_args());
}
function arr_uksort()
{
    return \Arrgh\Arrgh::uksort(...func_get_args());
}
function arr_usort()
{
    return \Arrgh\Arrgh::usort(...func_get_args());
}
function arr_multisort()
{
    return \Arrgh\Arrgh::multisort(...func_get_args());
}
function arr_pop()
{
    return \Arrgh\Arrgh::pop(...func_get_args());
}
function arr_shift()
{
    return \Arrgh\Arrgh::shift(...func_get_args());
}
function arr_end()
{
    return \Arrgh\Arrgh::end(...func_get_args());
}

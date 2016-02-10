<?php
function arr($array = []) {
    return new Arrgh($array);
}
function arr_collapse()
{
    return Arrgh::collapse(...func_get_args());
}
function arr_contains()
{
    return Arrgh::contains(...func_get_args());
}
function arr_except()
{
    return Arrgh::except(...func_get_args());
}
function arr_map_assoc()
{
    return Arrgh::map_assoc(...func_get_args());
}
function arr_only()
{
    return Arrgh::only(...func_get_args());
}
function arr_sort_by()
{
    return Arrgh::sort_by(...func_get_args());
}
function arr_depth()
{
    return Arrgh::depth(...func_get_args());
}
function arr_even()
{
    return Arrgh::even(...func_get_args());
}
function arr_first()
{
    return Arrgh::first(...func_get_args());
}
function arr_get()
{
    return Arrgh::get(...func_get_args());
}
function arr_head()
{
    return Arrgh::head(...func_get_args());
}
function arr_is_collection()
{
    return Arrgh::is_collection(...func_get_args());
}
function arr_last()
{
    return Arrgh::last(...func_get_args());
}
function arr_odd()
{
    return Arrgh::odd(...func_get_args());
}
function arr_partition()
{
    return Arrgh::partition(...func_get_args());
}
function arr_tail()
{
    return Arrgh::tail(...func_get_args());
}
function arr_change_key_case()
{
    return Arrgh::change_key_case(...func_get_args());
}
function arr_chunk()
{
    return Arrgh::chunk(...func_get_args());
}
function arr_column()
{
    return Arrgh::column(...func_get_args());
}
function arr_combine()
{
    return Arrgh::combine(...func_get_args());
}
function arr_count_values()
{
    return Arrgh::count_values(...func_get_args());
}
function arr_diff()
{
    return Arrgh::diff(...func_get_args());
}
function arr_diff_assoc()
{
    return Arrgh::diff_assoc(...func_get_args());
}
function arr_diff_key()
{
    return Arrgh::diff_key(...func_get_args());
}
function arr_diff_uassoc()
{
    return Arrgh::diff_uassoc(...func_get_args());
}
function arr_diff_ukey()
{
    return Arrgh::diff_ukey(...func_get_args());
}
function arr_fill()
{
    return Arrgh::fill(...func_get_args());
}
function arr_fill_keys()
{
    return Arrgh::fill_keys(...func_get_args());
}
function arr_filter()
{
    return Arrgh::filter(...func_get_args());
}
function arr_flip()
{
    return Arrgh::flip(...func_get_args());
}
function arr_intersect()
{
    return Arrgh::intersect(...func_get_args());
}
function arr_intersect_assoc()
{
    return Arrgh::intersect_assoc(...func_get_args());
}
function arr_intersect_key()
{
    return Arrgh::intersect_key(...func_get_args());
}
function arr_intersect_uassoc()
{
    return Arrgh::intersect_uassoc(...func_get_args());
}
function arr_intersect_ukey()
{
    return Arrgh::intersect_ukey(...func_get_args());
}
function arr_keys()
{
    return Arrgh::keys(...func_get_args());
}
function arr_merge()
{
    return Arrgh::merge(...func_get_args());
}
function arr_merge_recursive()
{
    return Arrgh::merge_recursive(...func_get_args());
}
function arr_pad()
{
    return Arrgh::pad(...func_get_args());
}
function arr_product()
{
    return Arrgh::product(...func_get_args());
}
function arr_rand()
{
    return Arrgh::rand(...func_get_args());
}
function arr_reduce()
{
    return Arrgh::reduce(...func_get_args());
}
function arr_replace()
{
    return Arrgh::replace(...func_get_args());
}
function arr_replace_recursive()
{
    return Arrgh::replace_recursive(...func_get_args());
}
function arr_reverse()
{
    return Arrgh::reverse(...func_get_args());
}
function arr_slice()
{
    return Arrgh::slice(...func_get_args());
}
function arr_sum()
{
    return Arrgh::sum(...func_get_args());
}
function arr_udiff()
{
    return Arrgh::udiff(...func_get_args());
}
function arr_udiff_assoc()
{
    return Arrgh::udiff_assoc(...func_get_args());
}
function arr_udiff_uassoc()
{
    return Arrgh::udiff_uassoc(...func_get_args());
}
function arr_uintersect()
{
    return Arrgh::uintersect(...func_get_args());
}
function arr_uintersect_assoc()
{
    return Arrgh::uintersect_assoc(...func_get_args());
}
function arr_uintersect_uassoc()
{
    return Arrgh::uintersect_uassoc(...func_get_args());
}
function arr_unique()
{
    return Arrgh::unique(...func_get_args());
}
function arr_values()
{
    return Arrgh::values(...func_get_args());
}
function arr_count()
{
    return Arrgh::count(...func_get_args());
}
function arr_max()
{
    return Arrgh::max(...func_get_args());
}
function arr_min()
{
    return Arrgh::min(...func_get_args());
}
function arr_range()
{
    return Arrgh::range(...func_get_args());
}
function arr_sizeof()
{
    return Arrgh::sizeof(...func_get_args());
}
function arr_map()
{
    return Arrgh::map(...func_get_args());
}
function arr_key_exists()
{
    return Arrgh::key_exists(...func_get_args());
}
function arr_search()
{
    return Arrgh::search(...func_get_args());
}
function arr_implode()
{
    return Arrgh::implode(...func_get_args());
}
function arr_in_array()
{
    return Arrgh::in_array(...func_get_args());
}
function arr_join()
{
    return Arrgh::join(...func_get_args());
}
function arr_push()
{
    return Arrgh::push(...func_get_args());
}
function arr_splice()
{
    return Arrgh::splice(...func_get_args());
}
function arr_unshift()
{
    return Arrgh::unshift(...func_get_args());
}
function arr_walk()
{
    return Arrgh::walk(...func_get_args());
}
function arr_walk_recursive()
{
    return Arrgh::walk_recursive(...func_get_args());
}
function arr_arsort()
{
    return Arrgh::arsort(...func_get_args());
}
function arr_asort()
{
    return Arrgh::asort(...func_get_args());
}
function arr_krsort()
{
    return Arrgh::krsort(...func_get_args());
}
function arr_ksort()
{
    return Arrgh::ksort(...func_get_args());
}
function arr_natcasesort()
{
    return Arrgh::natcasesort(...func_get_args());
}
function arr_natsort()
{
    return Arrgh::natsort(...func_get_args());
}
function arr_rsort()
{
    return Arrgh::rsort(...func_get_args());
}
function arr_shuffle()
{
    return Arrgh::shuffle(...func_get_args());
}
function arr_sort()
{
    return Arrgh::sort(...func_get_args());
}
function arr_uasort()
{
    return Arrgh::uasort(...func_get_args());
}
function arr_uksort()
{
    return Arrgh::uksort(...func_get_args());
}
function arr_usort()
{
    return Arrgh::usort(...func_get_args());
}
function arr_multisort()
{
    return Arrgh::multisort(...func_get_args());
}
function arr_pop()
{
    return Arrgh::pop(...func_get_args());
}
function arr_shift()
{
    return Arrgh::shift(...func_get_args());
}
function arr_end()
{
    return Arrgh::end(...func_get_args());
}
